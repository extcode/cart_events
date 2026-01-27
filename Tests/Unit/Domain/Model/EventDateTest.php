<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Tests\Unit\Domain\Model;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\CartEvents\Domain\Model\AbstractEventDate;
use Extcode\CartEvents\Domain\Model\EventDate;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversClass(EventDate::class)]
class EventDateTest extends UnitTestCase
{
    protected EventDate $eventDate;

    protected function setUp(): void
    {
        $this->eventDate = new EventDate();
    }

    protected function tearDown(): void
    {
        unset($this->eventDate);
    }

    #[Test]
    public function eventDateExtendsAbstractEventDate(): void
    {
        self::assertInstanceOf(AbstractEventDate::class, $this->eventDate);
    }

    #[Test]
    public function getSkuReturnsInitialValueForSku(): void
    {
        self::assertSame(
            '',
            $this->eventDate->getSku()
        );
    }

    #[Test]
    public function setSkuSetsSku(): void
    {
        $this->eventDate->setSku('sku');

        self::assertSame(
            'sku',
            $this->eventDate->getSku()
        );
    }

    #[Test]
    public function getTitleReturnsInitialValueForTitle(): void
    {
        self::assertSame(
            '',
            $this->eventDate->getTitle()
        );
    }

    #[Test]
    public function setTitleSetsTitle(): void
    {
        $this->eventDate->setTitle('Title');

        self::assertSame(
            'Title',
            $this->eventDate->getTitle()
        );
    }

    #[Test]
    public function getLocationReturnsInitialValueForLocation(): void
    {
        self::assertSame(
            '',
            $this->eventDate->getLocation()
        );
    }

    #[Test]
    public function setLocationSetsLocation(): void
    {
        $this->eventDate->setLocation('Location');

        self::assertSame(
            'Location',
            $this->eventDate->getLocation()
        );
    }

    #[Test]
    public function getLecturerReturnsInitialValueForLecturer(): void
    {
        self::assertSame(
            '',
            $this->eventDate->getLecturer()
        );
    }

    #[Test]
    public function setLecturerSetsLecturer(): void
    {
        $this->eventDate->setLecturer('Lecturer');

        self::assertSame(
            'Lecturer',
            $this->eventDate->getLecturer()
        );
    }

    #[Test]
    public function isBookableReturnsInitialValueForBookable(): void
    {
        self::assertFalse(
            $this->eventDate->isBookable()
        );
    }

    #[Test]
    public function setBookableSetsBookable(): void
    {
        $this->eventDate->setBookable(true);

        self::assertTrue(
            $this->eventDate->isBookable()
        );
    }

    #[Test]
    public function isPriceCategorizedReturnsInitialValueForPriceCategorized(): void
    {
        self::assertFalse(
            $this->eventDate->isPriceCategorized()
        );
    }

    #[Test]
    public function setPriceCategorizedSetsPriceCategorized(): void
    {
        $this->eventDate->setPriceCategorized(true);

        self::assertTrue(
            $this->eventDate->isPriceCategorized()
        );
    }

    #[Test]
    public function isHandleSeatsReturnsInitialValueForHandleSeats(): void
    {
        self::assertFalse(
            $this->eventDate->isHandleSeats()
        );
    }

    #[Test]
    public function setHandleSeatsSetsHandleSeats(): void
    {
        $this->eventDate->setHandleSeats(true);

        self::assertTrue(
            $this->eventDate->isHandleSeats()
        );
    }

    #[Test]
    public function isHandleSeatsInPriceCategoryReturnsInitialValueForHandleSeatsInPriceCategory(): void
    {
        self::assertFalse(
            $this->eventDate->isHandleSeatsInPriceCategory()
        );
    }

    #[Test]
    public function setHandleSeatsInPriceCategorySetsHandleSeatsInPriceCategory(): void
    {
        $this->eventDate->setHandleSeatsInPriceCategory(true);

        self::assertTrue(
            $this->eventDate->isHandleSeatsInPriceCategory()
        );
    }

    #[Test]
    public function getSeatsNumberReturnsZeroIfHandleSeatsIsFalse()
    {
        self::assertSame(
            0,
            $this->eventDate->getSeatsNumber()
        );

        $this->eventDate->setSeatsNumber(15);

        self::assertSame(
            0,
            $this->eventDate->getSeatsNumber()
        );
    }

    #[Test]
    public function getSeatsNumberReturnsInitialValueForSeatsNumberIfHandleSeatsIsTrue()
    {
        $this->eventDate->setHandleSeats(true);

        self::assertSame(
            0,
            $this->eventDate->getSeatsNumber()
        );
    }

    #[Test]
    public function setSeatsNumberSetsSeatsNumber()
    {
        $this->eventDate->setSeatsNumber(15);

        self::assertSame(
            0,
            $this->eventDate->getSeatsNumber()
        );

        $this->eventDate->setHandleSeats(true);

        self::assertSame(
            15,
            $this->eventDate->getSeatsNumber()
        );
    }

    #[Test]
    public function getSeatsTakenReturnsZeroIfHandleSeatsIsFalse()
    {
        self::assertSame(
            0,
            $this->eventDate->getSeatsTaken()
        );

        $this->eventDate->setSeatsTaken(15);

        self::assertSame(
            0,
            $this->eventDate->getSeatsTaken()
        );
    }

    #[Test]
    public function getSeatsTakenReturnsInitialValueForSeatsTakenIfHandleSeatsIsTrue()
    {
        $this->eventDate->setHandleSeats(true);

        self::assertSame(
            0,
            $this->eventDate->getSeatsTaken()
        );
    }

    #[Test]
    public function setSeatsTakenSetsSeatsTaken()
    {
        $this->eventDate->setSeatsTaken(15);

        self::assertSame(
            0,
            $this->eventDate->getSeatsTaken()
        );

        $this->eventDate->setHandleSeats(true);

        self::assertSame(
            15,
            $this->eventDate->getSeatsTaken()
        );
    }

    #[Test]
    public function getSeatsAvailableReturnsZeroIfHandleSeatsIsFalse()
    {
        self::assertSame(
            0,
            $this->eventDate->getSeatsAvailable()
        );

        $this->eventDate->setSeatsNumber(15);

        self::assertSame(
            0,
            $this->eventDate->getSeatsAvailable()
        );
    }

    #[Test]
    public function getSeatsAvailableReturnsDifferenceOfInitialValueForSeatsNumberAndSeatsTakenIfHandleSeatsIsTrue()
    {
        $this->eventDate->setHandleSeats(true);

        self::assertSame(
            0,
            $this->eventDate->getSeatsAvailable()
        );
    }

    #[Test]
    public function getSeatsAvailableDifferenceOfValueForSeatsNumberAndSeatsTakenIfHandleSeatsIsTrue()
    {
        $this->eventDate->setSeatsNumber(30);
        $this->eventDate->setSeatsTaken(13);

        self::assertSame(
            0,
            $this->eventDate->getSeatsAvailable()
        );

        $this->eventDate->setHandleSeats(true);

        self::assertSame(
            17,
            $this->eventDate->getSeatsAvailable()
        );
    }

    #[TEST]
    public function isAvailableReturnsFalseIfBookableIsFalse()
    {
        $this->eventDate->setHandleSeats(true);
        $this->eventDate->setSeatsNumber(20);
        $this->eventDate->setBookable(false);

        self::assertFalse(
            $this->eventDate->isAvailable()
        );

        $this->eventDate->setBookable(true);

        self::assertTrue(
            $this->eventDate->isAvailable()
        );
    }

    #[TEST]
    public function isAvailableReturnsTrueIfIsBookableAndHandleSeatsIsFalse()
    {
        $this->eventDate->setBookable(true);
        $this->eventDate->setHandleSeats(false);
        self::assertTrue(
            $this->eventDate->isAvailable()
        );

        $this->eventDate->setHandleSeats(true);
        self::assertFalse(
            $this->eventDate->isAvailable()
        );
    }

    #[TEST]
    public function isAvailableReturnsTrueIfIsBookableAndHandleSeatsIsTrueAndNumberOfSeatsIsGreaterThanZero()
    {
        $this->eventDate->setBookable(true);
        $this->eventDate->setHandleSeats(true);
        $this->eventDate->setSeatsNumber(2);
        self::assertTrue(
            $this->eventDate->isAvailable()
        );

        $this->eventDate->setSeatsTaken(1);
        self::assertTrue(
            $this->eventDate->isAvailable()
        );

        $this->eventDate->setSeatsTaken(2);
        self::assertFalse(
            $this->eventDate->isAvailable()
        );
    }

    #[TEST]
    public function isAvailableReturnsFalseIfIsBookableAndHandleSeatsIsTrueAndNumberOfSeatsIsLowerOrEqualToZero()
    {
        $this->eventDate->setBookable(true);
        $this->eventDate->setHandleSeats(true);
        $this->eventDate->setSeatsNumber(0);
        self::assertFalse(
            $this->eventDate->isAvailable()
        );

        $this->eventDate->setSeatsTaken(1);
        self::assertFalse(
            $this->eventDate->isAvailable()
        );
    }
}
