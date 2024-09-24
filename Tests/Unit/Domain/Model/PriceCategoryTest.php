<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Tests\Unit\Domain\Model;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\CartEvents\Domain\Model\PriceCategory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversClass(PriceCategory::class)]
class PriceCategoryTest extends UnitTestCase
{
    protected PriceCategory $priceCategory;

    protected function setUp(): void
    {
        $this->priceCategory = new PriceCategory();
    }

    protected function tearDown(): void
    {
        unset($this->priceCategory);
    }

    #[Test]
    public function getSkuReturnsInitialValueForSku(): void
    {
        self::assertSame(
            '',
            $this->priceCategory->getSku()
        );
    }

    #[Test]
    public function setSkuSetsSku(): void
    {
        $this->priceCategory->setSku('sku');

        self::assertSame(
            'sku',
            $this->priceCategory->getSku()
        );
    }

    #[Test]
    public function getTitleReturnsInitialValueForTitle(): void
    {
        self::assertSame(
            '',
            $this->priceCategory->getTitle()
        );
    }

    #[Test]
    public function setTitleSetsTitle(): void
    {
        $this->priceCategory->setTitle('Title');

        self::assertSame(
            'Title',
            $this->priceCategory->getTitle()
        );
    }

    #[Test]
    public function getPriceReturnsInitialValueForPrice(): void
    {
        self::assertSame(
            0.0,
            $this->priceCategory->getPrice()
        );
    }

    #[Test]
    public function setPriceSetsPrice(): void
    {
        $this->priceCategory->setPrice(19.99);

        self::assertSame(
            19.99,
            $this->priceCategory->getPrice()
        );
    }

    // todo: specialPrice

    #[Test]
    public function getSeatsNumberReturnsInitialValueForSeatsNumber(): void
    {
        self::assertSame(
            0,
            $this->priceCategory->getSeatsNumber()
        );
    }

    #[Test]
    public function setSeatsNumberSetsSeatsNumber(): void
    {
        $this->priceCategory->setSeatsNumber(42);

        self::assertSame(
            42,
            $this->priceCategory->getSeatsNumber()
        );
    }

    #[Test]
    public function getSeatsTakenReturnsInitialValueForSeatsTaken(): void
    {
        self::assertSame(
            0,
            $this->priceCategory->getSeatsTaken()
        );
    }

    #[Test]
    public function setSeatsTakenSetsSeatsTaken(): void
    {
        $this->priceCategory->setSeatsTaken(42);

        self::assertSame(
            42,
            $this->priceCategory->getSeatsTaken()
        );
    }

    // todo getSeatsAvailable, specialPrice, isAvailable, isBookable
}
