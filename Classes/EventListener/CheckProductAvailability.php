<?php

declare(strict_types=1);

namespace Extcode\CartEvents\EventListener;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Exception;
use Extcode\Cart\Domain\Model\Cart\Cart;
use Extcode\Cart\Domain\Model\Cart\ProductInterface;
use Extcode\Cart\Event\CheckProductAvailabilityEvent;
use Extcode\CartEvents\Domain\Model\EventDate;
use Extcode\CartEvents\Domain\Model\PriceCategory;
use Extcode\CartEvents\Domain\Repository\EventDateRepository;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

final readonly class CheckProductAvailability
{
    public function __construct(
        private EventDateRepository $eventDateRepository,
    ) {}

    public function __invoke(CheckProductAvailabilityEvent $listenerEvent): void
    {
        $cart = $listenerEvent->getCart();
        $cartProduct = $listenerEvent->getProduct();
        $quantity = $listenerEvent->getQuantity();
        $mode = $listenerEvent->getMode();

        if ($cartProduct->getProductType() !== 'CartEvents') {
            return;
        }

        $eventDate = $this->retrieveEventDateFromDatabase($cartProduct);

        if (!$eventDate->isHandleSeats()) {
            return;
        }

        if ($eventDate->isHandleSeatsInPriceCategory() === false) {
            $this->hasEventDateEnoughSeats($eventDate, $cartProduct, $cart, $mode, (int)$quantity, $listenerEvent);
            return;
        }

        foreach ($eventDate->getPriceCategories() as $priceCategory) {
            $beVariantId = PriceCategory::class . '-' . $priceCategory->getUid();
            if (array_key_exists($beVariantId, $cartProduct->getBeVariants()) === false) {
                continue;
            }
            $this->hasPriceCategoryEnoughSeats($cartProduct, $cart, $mode, $beVariantId, (int)$quantity, $priceCategory, $listenerEvent);
        }
    }

    private function retrieveEventDateFromDatabase(ProductInterface $cartProduct): EventDate
    {
        $querySettings = $this->eventDateRepository->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $this->eventDateRepository->setDefaultQuerySettings($querySettings);

        $eventDate = $this->eventDateRepository->findByIdentifier($cartProduct->getProductId());

        if (($eventDate instanceof EventDate) === false) {
            throw new Exception('Can not find EventDate with uid ' . $cartProduct->getProductId() . '.', 1769634921);
        }

        return $eventDate;
    }

    private function hasEventDateEnoughSeats(
        EventDate $eventDate,
        ProductInterface $cartProduct,
        Cart $cart,
        string $mode,
        int $quantity,
        CheckProductAvailabilityEvent $listenerEvent
    ): void {
        if (($mode === 'add') && $cart->getProductById($cartProduct->getId())) {
            $quantity += $cart->getProductById($cartProduct->getId())->getQuantity();
        }

        if ($quantity > $eventDate->getSeatsAvailable()) {
            $listenerEvent->setAvailable(false);
            $listenerEvent->addMessage(
                GeneralUtility::makeInstance(
                    FlashMessage::class,
                    LocalizationUtility::translate(
                        'tx_cart.error.stock_handling.update',
                        'cart'
                    ),
                    '',
                    ContextualFeedbackSeverity::ERROR
                )
            );
        }
    }

    private function hasPriceCategoryEnoughSeats(
        ProductInterface $cartProduct,
        Cart $cart,
        string $mode,
        string $beVariantId,
        int $quantity,
        $priceCategory,
        CheckProductAvailabilityEvent $listenerEvent
    ): void {
        if (($mode === 'add') && $cart->getProductById($cartProduct->getId())) {
            if ($cart->getProductById($cartProduct->getId())->getBeVariantById($beVariantId)) {
                $quantity += $cart->getProductById($cartProduct->getId())->getBeVariantById($beVariantId)->getQuantity();
            }
        }
        if ($quantity > $priceCategory->getSeatsAvailable()) {
            $listenerEvent->setAvailable(false);
            $listenerEvent->addMessage(
                GeneralUtility::makeInstance(
                    FlashMessage::class,
                    LocalizationUtility::translate(
                        'tx_cart.error.stock_handling.update',
                        'cart'
                    ),
                    '',
                    ContextualFeedbackSeverity::ERROR
                )
            );
        }
    }
}
