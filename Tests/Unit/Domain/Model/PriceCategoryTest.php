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
use Extcode\CartEvents\Tests\ObjectAccess;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversClass(PriceCategory::class)]
class PriceCategoryTest extends UnitTestCase
{
    protected PriceCategory $priceCategory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->priceCategory = new PriceCategory();
    }

    protected function tearDown(): void
    {
        unset($this->priceCategory);

        parent::tearDown();
    }

    #[Test]
    public function getSkuReturnsValueForSku(): void
    {
        $priceCategory = new PriceCategory();

        self::assertSame(
            '',
            $priceCategory->getSku()
        );

        ObjectAccess::setProperty($priceCategory, 'sku', 'sku');

        self::assertSame(
            'sku',
            $priceCategory->getSku()
        );
    }

    #[Test]
    public function getTitleReturnsTitle(): void
    {
        $priceCategory = new PriceCategory();

        self::assertSame(
            '',
            $priceCategory->getTitle()
        );

        ObjectAccess::setProperty($priceCategory, 'title', 'title');

        self::assertSame(
            'title',
            $priceCategory->getTitle()
        );
    }

    #[Test]
    public function getPriceReturnsPrice(): void
    {
        $priceCategory = new PriceCategory();

        self::assertSame(
            0.0,
            $priceCategory->getPrice()
        );

        ObjectAccess::setProperty($priceCategory, 'price', 12.88);

        self::assertSame(
            12.88,
            $priceCategory->getPrice()
        );
    }

    // todo: specialPrice

    #[Test]
    public function getSeatsNumberReturnsSeatsNumber(): void
    {
        $priceCategory = new PriceCategory();

        self::assertSame(
            0,
            $priceCategory->getSeatsNumber()
        );

        ObjectAccess::setProperty($priceCategory, 'seatsNumber', 42);

        self::assertSame(
            42,
            $priceCategory->getSeatsNumber()
        );
    }

    #[Test]
    public function getSeatsTakenReturnsInitialValueForSeatsTaken(): void
    {
        $priceCategory = new PriceCategory();

        self::assertSame(
            0,
            $priceCategory->getSeatsTaken()
        );

        ObjectAccess::setProperty($priceCategory, 'seatsTaken', 42);

        self::assertSame(
            42,
            $priceCategory->getSeatsTaken()
        );
    }

    #[Test]
    public function setSeatsTakenSetsSeatsTaken()
    {
        $priceCategory = new PriceCategory();

        ObjectAccess::setProperty($priceCategory, 'seatsTaken', 15);

        self::assertSame(
            15,
            $priceCategory->getSeatsTaken()
        );
    }

    // todo getSeatsAvailable, specialPrice, isAvailable, isBookable
}
