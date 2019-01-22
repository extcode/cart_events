<?php
declare(strict_types=1);

namespace Extcode\CartEvents\Domain\Finisher\Form;

use Extcode\Cart\Domain\Model\Cart\Cart;
use Extcode\Cart\Domain\Model\Cart\FeVariant;
use Extcode\Cart\Domain\Model\Cart\Product;
use Extcode\CartEvents\Domain\Model\EventDate;
use Extcode\CartEvents\Domain\Repository\EventDateRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AddToCartFinisher implements \Extcode\Cart\Domain\Finisher\Form\AddToCartFinisherInterface
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
    protected $eventDateRepository;

    /**
     * @param array $formValues
     * @param Cart $cart
     */
    public function getProductFromForm(
        array $formValues,
        Cart $cart
    ) {
        $errors = [];

        $eventDateId = $formValues['productUid'];

        unset($formValues['productType']);
        unset($formValues['productUid']);

        $this->objectManager = GeneralUtility::makeInstance(
            \TYPO3\CMS\Extbase\Object\ObjectManager::class
        );
        $this->eventDateRepository = $this->objectManager->get(
            EventDateRepository::class
        );
        $eventDate = $this->eventDateRepository->findByUid((int)$eventDateId);
        $quantity = 1;

        $newProduct = $this->getProductFromEventDate($eventDate, $quantity, $cart->getTaxClasses(), $formValues);
        $newProduct->setMaxNumberInCart(1);
        $newProduct->setMinNumberInCart(1);

        return [$errors, [$newProduct]];
    }

    /**
     * @param EventDate $eventDate
     * @param int $quantity
     * @param array $taxClasses
     * @param array $feVariants
     *
     * @return Product
     */
    protected function getProductFromEventDate(
        EventDate $eventDate,
        int $quantity,
        array $taxClasses,
        array $feVariants = null
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
            $this->getFeVariant($feVariants)
        );
        $product->setIsVirtualProduct($event->isVirtualProduct());

        return $product;
    }

    /**
     * @var array $data
     * @return FeVariant|null
     */
    protected function getFeVariant(array $data)
    {
        $feVariant = null;

        if (!empty($data) && is_array($data)) {
            $feVariants = [];
            foreach ($data as $dataKey => $dataValue) {
                $feVariants[] = [
                    'sku' => $dataKey,
                    'title' => $dataKey,
                    'value' => $dataValue,
                ];
            }

            $feVariant = $this->objectManager->get(
                FeVariant::class,
                $feVariants
            );
        }
        return $feVariant;
    }
}
