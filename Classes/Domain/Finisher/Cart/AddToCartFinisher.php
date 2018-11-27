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

/**
 * CheckAvailability Hook
 *
 * @author Daniel Lorenz <ext.cart@extco.de>
 */
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
    protected $eventDateRepository;

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

        if ($request->hasArgument('quantities')) {
            $quantities = $request->getArgument('quantities');
            $quantity = (int)$quantities[$cartProduct->getId()];
        } elseif ($request->hasArgument('quantity')) {
            $quantity = (int)$request->getArgument('quantity');
        }

        if ($cartProduct->getProductType() != 'CartEvents') {
            return $availabilityResponse;
        }

        $this->eventDateRepository = $this->objectManager->get(
            EventDateRepository::class
        );

        $querySettings = $this->eventDateRepository->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $this->eventDateRepository->setDefaultQuerySettings($querySettings);

        $eventDate = $this->eventDateRepository->findByIdentifier($cartProduct->getProductId());

        if ($eventDate->isHandleSeats() && ($quantity > $eventDate->getSeatsAvailable())) {
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

        $errors = [];
        $cartProducts = [];

        if (!(int)$requestArguments['eventDate']) {
            $errors[] = [
                'messageBody' => LocalizationUtility::translate(
                    'tx_cartevents.error.invalid_event_date',
                    'cart_events'
                ),
                'severity' => AbstractMessage::ERROR
            ];
            return [$errors, $cartProducts];
        }

        $quantity = 0;

        if ((int)$requestArguments['quantity']) {
            $quantity = (int)$requestArguments['quantity'];
        }

        if ($quantity < 0) {
            $errors[] = [
                'messageBody' => LocalizationUtility::translate(
                    'tx_cart.error.invalid_quantity',
                    'cart_events'
                ),
                'severity' => AbstractMessage::WARNING
            ];

            return [$errors, $cartProducts];
        }

        $this->objectManager = GeneralUtility::makeInstance(
            ObjectManager::class
        );
        $this->eventDateRepository = $this->objectManager->get(
            EventDateRepository::class
        );

        $eventDate = $this->eventDateRepository->findByUid((int)$requestArguments['eventDate']);

        if (!$eventDate) {
            $errors[] = [
                'messageBody' => LocalizationUtility::translate(
                    'tx_cartevents.error.event_date_not_found',
                    'cart_events'
                ),
                'severity' => AbstractMessage::WARNING
            ];

            return [$errors, $cartProducts];
        }

        if (!$eventDate->isBookable()) {
            $errors[] = [
                'messageBody' => LocalizationUtility::translate(
                    'tx_cartevents.error.event_is_not_bookable',
                    'cart_events'
                ),
                'severity' => AbstractMessage::WARNING
            ];

            return [$errors, $cartProducts];
        }

        $newProduct = $this->getProductFromEventDate($eventDate, $quantity, $taxClasses);

        $this->checkAvailability($request, $newProduct, $cart);

        return [$errors, [$newProduct]];
    }

    /**
     * @param EventDate $eventDate
     * @param int $quantity
     * @param array $taxClasses
     *
     * @return Product
     */
    protected function getProductFromEventDate(
        EventDate $eventDate,
        int $quantity,
        array $taxClasses
    ) {
        $event = $eventDate->getEvent();
        $title = implode(' - ', [$event->getTitle(), $eventDate->getTitle()]);
        $sku = implode(' - ', [$event->getSku(), $eventDate->getSku()]);

        $product = new Product(
            'CartEvents',
            $eventDate->getUid(),
            $sku,
            $title,
            $eventDate->getBestSpecialPrice(),
            $taxClasses[$event->getTaxClassId()],
            $quantity,
            true,
            null
        );
        $product->setIsVirtualProduct($event->isVirtualProduct());

        return $product;
    }
}
