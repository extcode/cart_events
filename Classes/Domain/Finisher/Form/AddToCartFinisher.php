<?php
declare(strict_types=1);

namespace Extcode\CartEvents\Domain\Finisher\Form;

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
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
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
     * @var PriceCategoryRepository
     */
    protected $priceCategoryRepository = null;

    /**
     * @var PriceCategory
     */
    protected $priceCategory = null;

    /**
     * @param array $formValues
     * @param Cart $cart
     */
    public function getProductFromForm(
        array $formValues,
        Cart $cart
    ) {
        $errors = [];

        if ($formValues['productType'] !== 'CartEvents') {
            return [$errors, []];
        }

        $eventDateId = $formValues['eventDateId'];
        $priceCategoryId = (int)$formValues['priceCategoryId'];

        unset($formValues['productType']);
        unset($formValues['eventDateId']);
        unset($formValues['priceCategoryId']);

        $this->objectManager = GeneralUtility::makeInstance(
            \TYPO3\CMS\Extbase\Object\ObjectManager::class
        );
        $this->eventDateRepository = $this->objectManager->get(
            EventDateRepository::class
        );
        $this->eventDate = $this->eventDateRepository->findByUid((int)$eventDateId);
        $quantity = 1;

        if ($priceCategoryId) {
            $this->priceCategoryRepository = $this->objectManager->get(
                PriceCategoryRepository::class
            );
            $this->priceCategory = $this->priceCategoryRepository->findByUid((int)$priceCategoryId);
        }

        $newProduct = $this->getProductFromEventDate($quantity, $cart->getTaxClasses(), $formValues);

        $newProduct->setMaxNumberInCart(1);
        $newProduct->setMinNumberInCart(1);

        return [$errors, [$newProduct]];
    }

    /**
     * @param int $quantity
     * @param array $taxClasses
     * @param array $feVariants
     *
     * @return Product
     */
    protected function getProductFromEventDate(
        int $quantity,
        array $taxClasses,
        array $feVariants = null
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
        $cartBackendVariant = $this->objectManager->get(
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

    /**
     * @var array $data
     * @return FeVariant|null
     */
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

            $feVariant = $this->objectManager->get(
                FeVariant::class,
                $feVariants
            );
        }
        return $feVariant;
    }
}
