<?php
declare(strict_types=1);
namespace Extcode\CartEvents\EventListener;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\Cart\Domain\Model\Cart\BeVariant;
use Extcode\Cart\Domain\Model\Cart\Cart;
use Extcode\Cart\Domain\Model\Cart\Product;
use Extcode\Cart\Event\RetrieveProductsFromRequestEvent;
use Extcode\CartEvents\Domain\Model\EventDate;
use Extcode\CartEvents\Domain\Model\PriceCategory;
use Extcode\CartEvents\Domain\Repository\EventDateRepository;
use Extcode\CartEvents\Domain\Repository\PriceCategoryRepository;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class RetrieveProductsFromRequest
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
        $this->priceCategoryRepository = $priceCategoryRepository;
    }

    public function __invoke(RetrieveProductsFromRequestEvent $event): void
    {
        $request = $event->getRequest();
        $this->cart = $event->getCart();
        $requestArguments = $request->getArguments();
        $taxClasses = $this->cart->getTaxClasses();

        $errors = $this->checkRequestArguments($requestArguments);

        if (!empty($errors)) {
            $event->setErrors($errors);
            return;
        }

        $quantity = (int)$requestArguments['quantity'];

        $this->eventDate = $this->eventDateRepository->findByUid((int)$requestArguments['eventDate']);

        if (!$this->eventDate) {
            $event->addError(
                [
                    'messageBody' => LocalizationUtility::translate(
                        'tx_cartevents.error.event_date_not_found',
                        'cart_events'
                    ),
                    'severity' => AbstractMessage::WARNING
                ]
            );
            return;
        }

        if (!$this->eventDate->isBookable()) {
            $event->addError(
                [
                    'messageBody' => LocalizationUtility::translate(
                        'tx_cartevents.error.event_is_not_bookable',
                        'cart_events'
                    ),
                    'severity' => AbstractMessage::WARNING
                ]
            );
            return;
        }

        if (isset($requestArguments['priceCategory'])) {
            if (!(int)$requestArguments['priceCategory']) {
                $event->addError(
                    [
                        'messageBody' => LocalizationUtility::translate(
                            'tx_cartevents.plugin.form.submit.error.invalid_event_date_category_price',
                            'cart_events'
                        ),
                        'severity' => AbstractMessage::ERROR
                    ]
                );
                return;
            }

            $this->priceCategory = $this->priceCategoryRepository->findByUid((int)$requestArguments['priceCategory']);

            if (!$this->priceCategory->isBookable()) {
                $event->addError(
                    [
                        'messageBody' => LocalizationUtility::translate(
                            'tx_cartevents.plugin.form.submit.error.event_date.price_category.is_not_bookable',
                            'cart_events'
                        ),
                        'severity' => AbstractMessage::WARNING
                    ]
                );
                return;
            }
        }

        $event->addProduct(
            $this->getProductFromEventDate($quantity, $taxClasses)
        );
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
        if ($this->priceCategory) {
            $price = $this->priceCategory->getBestPrice();
        }

        $inputIsNetPrice = (bool)GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('cart_events', 'inputIsNetPrice');

        $product = new Product(
            'CartEvents',
            $this->eventDate->getUid(),
            $sku,
            $title,
            $price,
            $taxClasses[$event->getTaxClassId()],
            $quantity,
            $inputIsNetPrice,
            null
        );
        $product->setIsVirtualProduct($event->isVirtualProduct());

        if ($this->priceCategory) {
            $product->addBeVariant($this->getProductBackendVariant($product, $quantity));
        }

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
     * @param Product $product
     * @param int $quantity
     *
     * @return BeVariant
     */
    protected function getProductBackendVariant(
        Product $product,
        int $quantity
    ): BeVariant {
        $cartBackendVariant = GeneralUtility::makeInstance(
            BeVariant::class,
            PriceCategory::class . '-' . $this->priceCategory->getUid(),
            $product,
            null,
            $this->priceCategory->getTitle(),
            $this->priceCategory->getSku(),
            1,
            $this->priceCategory->getBestPrice(),
            $quantity
        );

        /*
           TODO
            if ($bestSpecialPrice) {
                $cartBackendVariant->setSpecialPrice($bestSpecialPrice->getPrice());
            }
         */

        return $cartBackendVariant;
    }

    protected function checkRequestArguments(array $requestArguments): array
    {
        if (!(int)$requestArguments['eventDate']) {
            return [
                'messageBody' => LocalizationUtility::translate(
                    'tx_cartevents.plugin.form.submit.error.invalid_event_date',
                    'cart_events'
                ),
                'severity' => AbstractMessage::ERROR
            ];
        }

        if ((int)$requestArguments['quantity'] < 0) {
            return [
                'messageBody' => LocalizationUtility::translate(
                    'tx_cart.error.invalid_quantity',
                    'cart_events'
                ),
                'severity' => AbstractMessage::WARNING
            ];
        }

        return [];
    }
}
