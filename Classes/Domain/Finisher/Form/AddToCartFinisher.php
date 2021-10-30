<?php
declare(strict_types=1);
namespace Extcode\CartEvents\Domain\Finisher\Form;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\Cart\Domain\Finisher\Form\AddToCartFinisherInterface;
use Extcode\Cart\Domain\Model\Cart\BeVariant;
use Extcode\Cart\Domain\Model\Cart\Cart;
use Extcode\Cart\Domain\Model\Cart\FeVariant;
use Extcode\Cart\Domain\Model\Cart\Product;
use Extcode\CartEvents\Domain\Model\EventDate;
use Extcode\CartEvents\Domain\Model\PriceCategory;
use Extcode\CartEvents\Domain\Repository\EventDateRepository;
use Extcode\CartEvents\Domain\Repository\PriceCategoryRepository;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AddToCartFinisher implements AddToCartFinisherInterface
{

    /**
     * @var Cart
     */
    protected $cart;

    /**
     * @var EventDateRepository
     */
    protected $eventDateRepository;

    /**
     * @var EventDate
     */
    protected $eventDate;

    /**
     * @var PriceCategoryRepository
     */
    protected $priceCategoryRepository;

    /**
     * @var PriceCategory
     */
    protected $priceCategory;

    public function getProductFromForm(
        array $formValues,
        Cart $cart
    ): array {
        $errors = [];

        if ($formValues['productType'] !== 'CartEvents') {
            return [$errors, []];
        }

        $eventDateId = $formValues['eventDateId'];
        $priceCategoryId = (int)$formValues['priceCategoryId'];

        unset($formValues['productType']);
        unset($formValues['eventDateId']);
        unset($formValues['priceCategoryId']);

        $this->eventDateRepository = GeneralUtility::makeInstance(
            EventDateRepository::class
        );
        $this->eventDate = $this->eventDateRepository->findByUid((int)$eventDateId);
        $quantity = 1;

        if ($priceCategoryId) {
            $this->priceCategoryRepository = GeneralUtility::makeInstance(
                PriceCategoryRepository::class
            );
            $this->priceCategory = $this->priceCategoryRepository->findByUid((int)$priceCategoryId);
        }

        $newProduct = $this->getProductFromEventDate($quantity, $cart->getTaxClasses(), $formValues);

        $newProduct->setMaxNumberInCart(1);
        $newProduct->setMinNumberInCart(1);

        return [$errors, [$newProduct]];
    }

    protected function getProductFromEventDate(
        int $quantity,
        array $taxClasses,
        array $feVariants = null
    ): Product {
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
            $this->getFeVariant($feVariants)
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

    protected function getFeVariant(array $data): ?FeVariant
    {
        $feVariant = null;

        if (!empty($data) && is_array($data)) {
            $feVariants = [];
            foreach ($data as $dataKey => $dataValue) {
                if (!empty($dataKey) && !empty($dataValue)) {
                    $feVariants[] = [
                        'sku' => $dataKey,
                        'title' => $dataKey,
                        'value' => $dataValue,
                    ];
                }
            }

            $feVariant = GeneralUtility::makeInstance(
                FeVariant::class,
                $feVariants
            );
        }

        return $feVariant;
    }
}
