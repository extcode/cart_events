<?php
declare(strict_types=1);
namespace Extcode\CartEvents\EventListener;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\Cart\Domain\Model\Cart\Cart;
use Extcode\Cart\Domain\Model\Cart\Product;
use Extcode\Cart\Event\CheckProductAvailabilityEvent;
use Extcode\CartEvents\Domain\Model\EventDate;
use Extcode\CartEvents\Domain\Model\PriceCategory;
use Extcode\CartEvents\Domain\Repository\EventDateRepository;
use Extcode\CartEvents\Domain\Repository\PriceCategoryRepository;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class CheckProductAvailability
{
    /**
     * @var EventDateRepository
     */
    protected $eventDateRepository;

    /**
     * @var PriceCategoryRepository
     */
    protected $priceCategoryRepository;

    /**
     * @var Cart
     */
    protected $cart;

    /**
     * @var EventDate
     */
    protected $eventDate;

    /**
     * @var PriceCategory
     */
    protected $priceCategory;

    public function __construct(
        EventDateRepository $eventDateRepository,
        PriceCategoryRepository $priceCategoryRepository
    ) {
        $this->eventDateRepository = $eventDateRepository;
        $this->priceCategory = $priceCategoryRepository;
    }

    public function __invoke(CheckProductAvailabilityEvent $listenerEvent): void
    {
        $cart = $listenerEvent->getCart();
        $cartProduct = $listenerEvent->getProduct();
        $quantity = $listenerEvent->getQuantity();
        $mode = $listenerEvent->getMode();

        if ($cartProduct->getProductType() !== 'CartEvents') {
            return;
        }

        $this->retrieveEventDateFromDatabase($cartProduct);

        if (!$this->eventDate->isHandleSeats()) {
            return;
        }

        if (!$this->eventDate->isHandleSeatsInPriceCategory()) {
            $this->hasEventDateEnoughSeats($cartProduct, $cart, $mode, (int)$quantity, $listenerEvent);
            return;
        }

        foreach ($this->eventDate->getPriceCategories() as $priceCategory) {
            $beVariantId = PriceCategory::class . '-' . $priceCategory->getUid();
            $quantity = (int)$quantity[$beVariantId];
            $this->hasPriceCategoryEnoughSeats($cartProduct, $cart, $mode, $beVariantId, $quantity, $priceCategory, $listenerEvent);
        }
    }

    /**
     * @param Product $cartProduct
     */
    protected function retrieveEventDateFromDatabase(Product $cartProduct)
    {
        $querySettings = $this->eventDateRepository->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $this->eventDateRepository->setDefaultQuerySettings($querySettings);

        $this->eventDate = $this->eventDateRepository->findByIdentifier($cartProduct->getProductId());
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
            if ($request->hasArgument('priceCategory')) {
                $quantities[PriceCategory::class . '-' . $request->getArgument('priceCategory')] = $request->getArgument('quantity');

                return $quantities;
            }

            return $request->getArgument('quantity');
        }

        return 0;
    }

    /**
     * @param Product $cartProduct
     * @param Cart $cart
     * @param string $mode
     * @param int $quantity
     * @param CheckProductAvailabilityEvent $listenerEvent
     */
    protected function hasEventDateEnoughSeats(Product $cartProduct, Cart $cart, string $mode, int $quantity, CheckProductAvailabilityEvent $listenerEvent): void
    {
        if (($mode === 'add') && $cart->getProduct($cartProduct->getId())) {
            $quantity += $cart->getProduct($cartProduct->getId())->getQuantity();
        }

        if ($quantity > $this->eventDate->getSeatsAvailable()) {
            $listenerEvent->setAvailable(false);
            $listenerEvent->addMessage(
                GeneralUtility::makeInstance(
                    FlashMessage::class,
                    LocalizationUtility::translate(
                        'tx_cart.error.stock_handling.update',
                        'cart'
                    ),
                    '',
                    AbstractMessage::ERROR
                )
            );
        }
    }

    /**
     * @param Product $cartProduct
     * @param Cart $cart
     * @param string $mode
     * @param string $beVariantId
     * @param int $quantity
     * @param $priceCategory
     * @param CheckProductAvailabilityEvent $listenerEvent
     */
    protected function hasPriceCategoryEnoughSeats(Product $cartProduct, Cart $cart, string $mode, string $beVariantId, int $quantity, $priceCategory, CheckProductAvailabilityEvent $listenerEvent): void
    {
        if (($mode === 'add') && $cart->getProduct($cartProduct->getId())) {
            if ($cart->getProduct($cartProduct->getId())->getBeVariant($beVariantId)) {
                $quantity += (int)$cart->getProduct($cartProduct->getId())->getBeVariant($beVariantId)->getQuantity();
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
                    AbstractMessage::ERROR
                )
            );
        }
    }
}
