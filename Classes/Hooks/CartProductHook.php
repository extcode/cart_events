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
     * Slot Repository
     *
     * @var \Extcode\CartEvents\Domain\Repository\SlotRepository
     */
    protected $slotRepository;

    /**
     * @param array $params
     * @return bool
     */
    public function checkAvailability(array $params) : bool
    {
        $cartProduct = $params['cartProduct'];
        $quantity = (int)$params['quantity'];

        $this->objectManager = GeneralUtility::makeInstance(
            \TYPO3\CMS\Extbase\Object\ObjectManager::class
        );

        if ($cartProduct->getProductType() != 'CartEvents') {
            return true;
        }

        $this->slotRepository = $this->objectManager->get(
            \Extcode\CartEvents\Domain\Repository\SlotRepository::class
        );

        $querySettings = $this->slotRepository->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $this->slotRepository->setDefaultQuerySettings($querySettings);

        $slot = $this->slotRepository->findByIdentifier($cartProduct->getProductId());

        if (!$slot->isHandleSeats()) {
            return true;
        }

        if ($quantity <= $slot->getSeatsAvailable()) {
            return true;
        }

        return false;
    }

    /**
     * @param array $requestArguments
     * @param array $taxClasses
     *
     * @return array
     */
    public function getProductFromRequest(array $requestArguments, array $taxClasses)
    {
        $errors = [];
        $cartProducts = [];

        if (!(int)$requestArguments['slot']) {
            $errors[] = [
                'messageBody' => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'tx_cartevents.error.invalid_slot',
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
        $this->slotRepository = $this->objectManager->get(
            \Extcode\CartEvents\Domain\Repository\SlotRepository::class
        );

        $slot = $this->slotRepository->findByUid((int)$requestArguments['slot']);

        if (!$slot) {
            $errors[] = [
                'messageBody' => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'tx_cartevents.error.slot_not_found',
                    'cart_events'
                ),
                'severity' => \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING
            ];

            return [$errors, $cartProducts];
        }

        if (!$slot->isBookable()) {
            $errors[] = [
                'messageBody' => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'tx_cartevents.error.event_is_not_bookable',
                    'cart_events'
                ),
                'severity' => \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING
            ];

            return [$errors, $cartProducts];
        }

        /**
         * TODO:
         *
                if ($this->areEnoughSeatsAvailable($slot, $newProduct)) {
                    $this->cart->addProduct($newProduct);

                    $this->cartUtility->writeCartToSession($this->cart, $this->cartFrameworkConfig['settings']);

                    $message = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                        'tx_cartevents.plugin.form.submit.success',
                        'cart_events'
                    );
                }
         */
        $topic = null;
        $date = null;

        $newProduct = $this->getProductFromSlot($slot, $quantity, $taxClasses);

        return [$errors, [$newProduct]];
    }

    /**
     * @param \Extcode\CartEvents\Domain\Model\Slot $slot
     * @param int $quantity
     * @param array $taxClasses
     *
     * @return \Extcode\Cart\Domain\Model\Cart\Product
     */
    protected function getProductFromSlot(
        \Extcode\CartEvents\Domain\Model\Slot $slot,
        int $quantity,
        array $taxClasses
    ) {
        $event = $slot->getEvent();
        $title = implode(' - ', [$event->getTitle(), $slot->getTitle()]);
        $sku = implode(' - ', [$event->getSku(), $slot->getSku()]);

        $product = new \Extcode\Cart\Domain\Model\Cart\Product(
            'CartEvents',
            $slot->getUid(),
            '2',
            0,
            $sku,
            $title,
            $slot->getBestSpecialPrice(),
            $taxClasses[$event->getTaxClassId()],
            $quantity,
            true,
            null
        );

        return $product;
    }

    /**
     * @param \Extcode\CartEvents\Domain\Model\Slot $slot
     * @param \Extcode\Cart\Domain\Model\Cart\Product $cartProduct
     *
     * @return bool
     */
    protected function areEnoughSeatsAvailable(
        \Extcode\CartEvents\Domain\Model\Slot $slot,
        \Extcode\Cart\Domain\Model\Cart\Product $cartProduct
    ) : bool {
        if (!$slot->isHandleSeats()) {
            return true;
        }

        $qty = $cartProduct->getQuantity();
        if ($this->cart->getProduct($cartProduct->getId())) {
            $qty += $this->cart->getProduct($cartProduct->getId())->getQuantity();
        }

        return $qty <= $slot->getSeatsAvailable();
    }
}
