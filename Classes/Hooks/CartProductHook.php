<?php
declare(strict_types=1);

namespace Extcode\CartEvents\Hooks;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * CheckAvailability Hook
 *
 * @author Daniel Lorenz <ext.cart@extco.de>
 */
class CartProductHook implements \Extcode\Cart\Hooks\CartProductHookInterface
{
    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Extcode\CartEvents\Domain\Repository\EventDateRepository
     */
    protected $eventDateRepository;

    /**
     * @param \TYPO3\CMS\Extbase\Mvc\Web\Request $request
     * @param \Extcode\Cart\Domain\Model\Cart\Product $cartProduct
     * @param \Extcode\Cart\Domain\Model\Cart\Cart $cart
     *
     * @return \Extcode\Cart\Domain\Model\Dto\AvailabilityResponse
     */
    public function checkAvailability(
        \TYPO3\CMS\Extbase\Mvc\Web\Request $request,
        \Extcode\Cart\Domain\Model\Cart\Product $cartProduct,
        \Extcode\Cart\Domain\Model\Cart\Cart $cart,
        string $mode = 'update'
    ) : \Extcode\Cart\Domain\Model\Dto\AvailabilityResponse {
        $this->objectManager = GeneralUtility::makeInstance(
            \TYPO3\CMS\Extbase\Object\ObjectManager::class
        );

        $availabilityResponse = GeneralUtility::makeInstance(
            \Extcode\Cart\Domain\Model\Dto\AvailabilityResponse::class
        );

        if ($request->hasArgument('quantities')) {
            $quantities = $request->getArgument('quantities');
            $quantity = (int)$quantities[$cartProduct->getId()];
        } else {
            if ($request->hasArgument('quantity')) {
                $quantity = (int)$request->getArgument('quantity');
            }
        }

        if ($cartProduct->getProductType() != 'CartEvents') {
            return $availabilityResponse;
        }

        $this->eventDateRepository = $this->objectManager->get(
            \Extcode\CartEvents\Domain\Repository\EventDateRepository::class
        );

        $querySettings = $this->eventDateRepository->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $this->eventDateRepository->setDefaultQuerySettings($querySettings);

        $eventDate = $this->eventDateRepository->findByIdentifier($cartProduct->getProductId());

        if ($eventDate->isHandleSeats() && ($quantity > $eventDate->getSeatsAvailable())) {
            $availabilityResponse->setAvailable(false);
            $flashMessage = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
                \TYPO3\CMS\Core\Messaging\FlashMessage::class,
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
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
     * @param \TYPO3\CMS\Extbase\Mvc\Web\Request $request
     * @param \Extcode\Cart\Domain\Model\Cart\Cart $cart
     *
     * @return array
     */
    public function getProductFromRequest(
        \TYPO3\CMS\Extbase\Mvc\Web\Request $request,
        \Extcode\Cart\Domain\Model\Cart\Cart $cart
    ) {
        $requestArguments = $request->getArguments();
        $taxClasses = $cart->getTaxClasses();

        $errors = [];
        $cartProducts = [];

        if (!(int)$requestArguments['eventDate']) {
            $errors[] = [
                'messageBody' => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'tx_cartevents.error.invalid_event_date',
                    'cart_events'
                ),
                'severity' => \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            ];
            return [$errors, $cartProducts];
        }

        $quantity = 0;

        if ((int)$requestArguments['quantity']) {
            $quantity = (int)$requestArguments['quantity'];
        }

        if ($quantity < 0) {
            $errors[] = [
                'messageBody' => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'tx_cart.error.invalid_quantity',
                    'cart_events'
                ),
                'severity' => \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING
            ];

            return [$errors, $cartProducts];
        }

        $this->objectManager = GeneralUtility::makeInstance(
            \TYPO3\CMS\Extbase\Object\ObjectManager::class
        );
        $this->eventDateRepository = $this->objectManager->get(
            \Extcode\CartEvents\Domain\Repository\EventDateRepository::class
        );

        $eventDate = $this->eventDateRepository->findByUid((int)$requestArguments['eventDate']);

        if (!$eventDate) {
            $errors[] = [
                'messageBody' => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'tx_cartevents.error.event_date_not_found',
                    'cart_events'
                ),
                'severity' => \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING
            ];

            return [$errors, $cartProducts];
        }

        if (!$eventDate->isBookable()) {
            $errors[] = [
                'messageBody' => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'tx_cartevents.error.event_is_not_bookable',
                    'cart_events'
                ),
                'severity' => \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING
            ];

            return [$errors, $cartProducts];
        }

        $newProduct = $this->getProductFromEventDate($eventDate, $quantity, $taxClasses);

        $this->checkAvailability($request, $newProduct, $cart);

        return [$errors, [$newProduct]];
    }

    /**
     * @param \Extcode\CartEvents\Domain\Model\EventDate $eventDate
     * @param int $quantity
     * @param array $taxClasses
     *
     * @return \Extcode\Cart\Domain\Model\Cart\Product
     */
    protected function getProductFromEventDate(
        \Extcode\CartEvents\Domain\Model\EventDate $eventDate,
        int $quantity,
        array $taxClasses
    ) {
        $event = $eventDate->getEvent();
        $title = implode(' - ', [$event->getTitle(), $eventDate->getTitle()]);
        $sku = implode(' - ', [$event->getSku(), $eventDate->getSku()]);

        $product = new \Extcode\Cart\Domain\Model\Cart\Product(
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
