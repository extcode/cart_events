<?php
declare(strict_types=1);

namespace Extcode\CartEvents\Domain\Finisher\Cart;

use Extcode\Cart\Domain\Finisher\Cart\AddToCartFinisherInterface;
use Extcode\Cart\Domain\Model\Cart\Cart;
use Extcode\Cart\Domain\Model\Cart\Product;
use Extcode\Cart\Domain\Model\Dto\AvailabilityResponse;
use Extcode\CartEvents\Domain\Model\EventDate;
use Extcode\CartEvents\Domain\Repository\EventDateRepository;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Web\Request;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class AddToCartFinisher implements AddToCartFinisherInterface
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Cart
     */
    protected $cart;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var EventDateRepository
     */
    protected $eventDateRepository = null;

    /**
     * @var EventDate
     */
    protected $eventDate = null;

    /**
     * @param Request $request
     * @param Product $cartProduct
     * @param Cart $cart
     * @param string $mode
     *
     * @return AvailabilityResponse
     */
    public function checkAvailability(
        Request $request,
        Product $cartProduct,
        Cart $cart,
        string $mode = 'update'
    ) : AvailabilityResponse {
        $this->objectManager = GeneralUtility::makeInstance(
            ObjectManager::class
        );

        /** @var AvailabilityResponse $availabilityResponse */
        $availabilityResponse = GeneralUtility::makeInstance(
            AvailabilityResponse::class
        );

        if ($cartProduct->getProductType() !== 'CartEvents') {
            return $availabilityResponse;
        }

        $this->retrieveEventDateFromDatabase($cartProduct);

        if (!$this->eventDate->isHandleSeats()) {
            return $availabilityResponse;
        }

        $quantities = $this->getQuantitiesFromRequest($request, $cartProduct);

        $quantity = (int)$quantities;
        return $this->hasEventDateEnoughSeats($cartProduct, $cart, $mode, $quantity, $availabilityResponse);
    }

    /**
     * @param Request $request
     * @param Cart $cart
     *
     * @return array
     */
    public function getProductFromRequest(
        Request $request,
        Cart $cart
    ) {
        $this->request = $request;
        $this->cart = $cart;

        $requestArguments = $request->getArguments();
        $taxClasses = $cart->getTaxClasses();

        $errors = $this->checkRequestArguments($requestArguments);

        if (!empty($errors)) {
            return [$errors, []];
        }

        $quantity = (int)$requestArguments['quantity'];

        $this->objectManager = GeneralUtility::makeInstance(
            ObjectManager::class
        );
        $this->eventDateRepository = $this->objectManager->get(
            EventDateRepository::class
        );

        $this->eventDate = $this->eventDateRepository->findByUid((int)$requestArguments['eventDate']);

        if (!$this->eventDate) {
            return [[
                'messageBody' => LocalizationUtility::translate(
                    'tx_cartevents.error.event_date_not_found',
                    'cart_events'
                ),
                'severity' => AbstractMessage::WARNING
            ], []];
        }

        if (!$this->eventDate->isBookable()) {
            return [[
                'messageBody' => LocalizationUtility::translate(
                    'tx_cartevents.error.event_is_not_bookable',
                    'cart_events'
                ),
                'severity' => AbstractMessage::WARNING
            ], []];
        }

        $newProduct = $this->getProductFromEventDate($quantity, $taxClasses);

        $this->checkAvailability($request, $newProduct, $cart);

        return [$errors, [$newProduct]];
    }

    /**
     * @param int $quantity
     * @param array $taxClasses
     *
     * @return Product
     */
    protected function getProductFromEventDate(
        int $quantity,
        array $taxClasses
    ) {
        $event = $this->eventDate->getEvent();
        $title = implode(' - ', [$event->getTitle(), $this->eventDate->getTitle()]);
        $sku = implode(' - ', [$event->getSku(), $this->eventDate->getSku()]);

        $price = $this->eventDate->getBestPrice();

        $product = new Product(
            'CartEvents',
            $this->eventDate->getUid(),
            $sku,
            $title,
            $price,
            $taxClasses[$event->getTaxClassId()],
            $quantity,
            true,
            null
        );
        $product->setIsVirtualProduct($event->isVirtualProduct());

        if ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cart_events']['getProductFromEventDate']) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cart_events']['getProductFromEventDate'] ?? [] as $className) {
                $params = [
                    'cart' => $this->cart,
                    'eventDate' => $this->eventDate,
                ];

                $_procObj = GeneralUtility::makeInstance($className);
                $_procObj->changeProductFromEventDate($product, $params);
            }
        }

        return $product;
    }

    /**
     * @param Product $cartProduct
     */
    protected function retrieveEventDateFromDatabase(Product $cartProduct)
    {
        $this->eventDateRepository = $this->objectManager->get(
            EventDateRepository::class
        );

        $querySettings = $this->eventDateRepository->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $this->eventDateRepository->setDefaultQuerySettings($querySettings);

        $this->eventDate = $this->eventDateRepository->findByIdentifier($cartProduct->getProductId());
    }

    /**
     * @param Product $cartProduct
     * @param Cart $cart
     * @param string $mode
     * @param int $quantity
     * @param AvailabilityResponse $availabilityResponse
     * @return AvailabilityResponse
     */
    protected function hasEventDateEnoughSeats(Product $cartProduct, Cart $cart, string $mode, int $quantity, AvailabilityResponse $availabilityResponse): AvailabilityResponse
    {
        if (($mode === 'add') && $cart->getProduct($cartProduct->getId())) {
            $quantity += $cart->getProduct($cartProduct->getId())->getQuantity();
        }

        if ($quantity > $this->eventDate->getSeatsAvailable()) {
            $availabilityResponse->setAvailable(false);
            $flashMessage = GeneralUtility::makeInstance(
                FlashMessage::class,
                LocalizationUtility::translate(
                    'tx_cart.error.stock_handling.update',
                    'cart'
                ),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );

            $availabilityResponse->addMessage($flashMessage);
        }

        return $availabilityResponse;
    }

    /**
     * @param Request $request
     * @param Product $cartProduct
     * @return mixed
     */
    protected function getQuantitiesFromRequest(Request $request, Product $cartProduct)
    {
        if ($request->hasArgument('quantities')) {
            $quantities = $request->getArgument('quantities');
            $quantities = $quantities[$cartProduct->getId()];
            return $quantities;
        }

        if ($request->hasArgument('quantity')) {
            return $request->getArgument('quantity');
        }

        return 0;
    }

    /**
     * @param array $requestArguments
     * @return array
     */
    protected function checkRequestArguments(array $requestArguments): array
    {
        if ((int)$requestArguments['quantity'] < 0) {
            return [
                'messageBody' => LocalizationUtility::translate(
                    'tx_cart.error.invalid_quantity',
                    'cart_events'
                ),
                'severity' => AbstractMessage::WARNING
            ];
        }

        if (!(int)$requestArguments['eventDate']) {
            return [
                'messageBody' => LocalizationUtility::translate(
                    'tx_cartevents.plugin.form.submit.error.invalid_event_date',
                    'cart_events'
                ),
                'severity' => AbstractMessage::ERROR
            ];
        }

        return [];
    }
}
