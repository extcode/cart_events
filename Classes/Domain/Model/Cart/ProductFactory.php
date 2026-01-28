<?php

namespace Extcode\CartEvents\Domain\Model\Cart;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\Cart\Domain\Model\Cart\BeVariant;
use Extcode\Cart\Domain\Model\Cart\FeVariant;
use Extcode\Cart\Domain\Model\Cart\Product;
use Extcode\CartEvents\Domain\Model\EventDate;
use Extcode\CartEvents\Domain\Model\PriceCategory;
use Extcode\CartEvents\Domain\Repository\EventDateRepository;
use Extcode\CartEvents\Domain\Repository\PriceCategoryRepository;
use Extcode\CartEvents\Exception\NotBookableException;
use InvalidArgumentException;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class ProductFactory implements ProductFactoryInterface
{
    public function __construct(
        private readonly EventDateRepository $eventDateRepository,
        private readonly PriceCategoryRepository $priceCategoryRepository,
    ) {}

    public function createProductFromRequestArguments(
        array $requestArguments,
        array $taxClasses,
        bool $isNetPrice,
    ): Product {
        if (isset($requestArguments['quantity']) === false) {
            throw new InvalidArgumentException('Quantity argument is missing', 1741700244);
        }

        if ((int)$requestArguments['quantity'] < 0) {
            throw new InvalidArgumentException('Quantity argument is invalid', 1741692900);
        }

        $quantity = (int)$requestArguments['quantity'];

        if (isset($requestArguments['eventDate']) === false) {
            throw new InvalidArgumentException('Event date argument is missing', 1741700304);
        }

        $eventDate = $this->getEventDateFromRequestArgument($requestArguments['eventDate']);

        $priceCategory = null;
        if (isset($requestArguments['priceCategory'])) {
            $priceCategory = $this->getPriceCategoryFromRequestArgument($requestArguments['priceCategory']);
        }

        $feVariant = null;
        if (isset($requestArguments['feVariant']) && ($requestArguments['feVariant'] instanceof FeVariant)) {
            $feVariant = $requestArguments['feVariant'];
        }

        return $this->getProductFromEventDate(
            $quantity,
            $taxClasses,
            $isNetPrice,
            $eventDate,
            $priceCategory,
            $feVariant
        );
    }

    private function getEventDateFromRequestArgument(
        mixed $identifier,
    ): EventDate {
        if (is_numeric($identifier) === false) {
            throw new InvalidArgumentException('Event date argument is invalid', 1741692831);
        }

        $eventDate = $this->eventDateRepository->findByUid($identifier);

        if (($eventDate instanceof EventDate) === false) {
            throw new InvalidArgumentException('Event date not found', 1741693220);
        }

        if ($eventDate->isBookable() === false) {
            throw new NotBookableException('Event date not bookable', 1741693273);
        }

        return $eventDate;
    }

    private function getPriceCategoryFromRequestArgument(
        int $identifier,
    ): PriceCategory {
        $priceCategory = $this->priceCategoryRepository->findByUid($identifier);

        if (($priceCategory instanceof PriceCategory) === false) {
            throw new InvalidArgumentException('Price category not found', 1741693444);
        }

        if (!$priceCategory->isBookable()) {
            throw new NotBookableException('Price category not bookable', 1741693482);
        }

        return $priceCategory;
    }

    private function getProductFromEventDate(
        int $quantity,
        array $taxClasses,
        bool $isNetPrice,
        EventDate $eventDate,
        ?PriceCategory $priceCategory = null,
        ?FeVariant $feVariant = null,
    ): Product {
        $event = $eventDate->getEvent();
        $title = implode(' - ', [$event->getTitle(), $eventDate->getTitle()]);
        $sku = implode(' - ', [$event->getSku(), $eventDate->getSku()]);

        $price = $eventDate->getPrice();
        $bestPrice = $eventDate->getBestPrice();
        if ($priceCategory instanceof PriceCategory) {
            $price = $priceCategory->getPrice();
            $bestPrice = $priceCategory->getBestPrice();
        }

        $product = new Product(
            'CartEvents',
            $eventDate->getUid(),
            $sku,
            $title,
            $price,
            $taxClasses[$event->getTaxClassId()],
            $quantity,
            $isNetPrice,
            $feVariant
        );
        $product->setIsVirtualProduct($event->isVirtualProduct());

        if ($bestPrice < $price) {
            $product->setSpecialPrice($bestPrice);
        }

        if ($priceCategory instanceof PriceCategory) {
            $product->addBeVariant($this->getProductBackendVariant($product, $quantity, $priceCategory));
        }

        return $product;
    }

    private function getProductBackendVariant(
        Product $product,
        int $quantity,
        PriceCategory $priceCategory,
    ): BeVariant {
        $cartBackendVariant = GeneralUtility::makeInstance(
            BeVariant::class,
            PriceCategory::class . '-' . $priceCategory->getUid(),
            $product,
            $priceCategory->getTitle(),
            $priceCategory->getSku(),
            1,
            $priceCategory->getBestPrice(),
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
}
