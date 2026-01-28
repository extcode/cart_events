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
use Extcode\CartEvents\Tests\ObjectAccess;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversClass(EventDate::class)]
class EventDateTest extends UnitTestCase
{
    protected EventDate $eventDate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventDate = new EventDate();
    }

    protected function tearDown(): void
    {
        unset($this->eventDate);

        parent::tearDown();
    }

    #[Test]
    public function eventDateExtendsAbstractEventDate(): void
    {
        self::assertInstanceOf(AbstractEventDate::class, $this->eventDate);
    }

    #[Test]
    public function getSkuReturnsSku(): void
    {
        $eventDate = new EventDate();

        self::assertSame(
            '',
            $eventDate->getSku()
        );

        ObjectAccess::setProperty($eventDate, 'sku', 'sku');

        self::assertSame(
            'sku',
            $eventDate->getSku()
        );
    }

    #[Test]
    public function getTitleReturnsTitle(): void
    {
        $eventDate = new EventDate();

        self::assertSame(
            '',
            $eventDate->getTitle()
        );

        ObjectAccess::setProperty($eventDate, 'title', 'title');

        self::assertSame(
            'title',
            $eventDate->getTitle()
        );
    }

    #[Test]
    public function getLocationReturnsLocation(): void
    {
        $eventDate = new EventDate();

        self::assertSame(
            '',
            $eventDate->getLocation()
        );

        ObjectAccess::setProperty($eventDate, 'location', 'location');

        self::assertSame(
            'location',
            $eventDate->getLocation()
        );
    }

    #[Test]
    public function getLecturerReturnsLecturer(): void
    {
        $eventDate = new EventDate();

        self::assertSame(
            '',
            $eventDate->getLecturer()
        );

        ObjectAccess::setProperty($eventDate, 'lecturer', 'lecturer');

        self::assertSame(
            'lecturer',
            $eventDate->getLecturer()
        );
    }

    #[Test]
    public function getImagesReturnsImages(): void
    {
        $images = $this->eventDate->getImages();

        self::assertSame(
            $images,
            $this->eventDate->getImages()
        );

        self::assertSame(
            0,
            $this->eventDate->getImages()->count()
        );

        $image1 = self::createStub(FileReference::class);
        $images->attach($image1);
        $image2 = self::createStub(FileReference::class);
        $images->attach($image2);

        self::assertSame(
            $images,
            $this->eventDate->getImages()
        );

        self::assertSame(
            2,
            $this->eventDate->getImages()->count()
        );
    }

    #[Test]
    public function getFirstImageReturnsFirstImage(): void
    {
        $images = $this->eventDate->getImages();

        self::assertNull(
            $this->eventDate->getFirstImage()
        );

        $image1 = self::createStub(FileReference::class);
        $images->attach($image1);
        $image2 = self::createStub(FileReference::class);
        $images->attach($image2);

        self::assertSame(
            $image1,
            $this->eventDate->getFirstImage()
        );
    }

    #[Test]
    public function getFilesReturnsFiles(): void
    {
        $files = $this->eventDate->getFiles();

        self::assertSame(
            $files,
            $this->eventDate->getFiles()
        );

        self::assertSame(
            0,
            $this->eventDate->getFiles()->count()
        );

        $file1 = self::createStub(FileReference::class);
        $files->attach($file1);
        $file2 = self::createStub(FileReference::class);
        $files->attach($file2);

        self::assertSame(
            $files,
            $this->eventDate->getFiles()
        );

        self::assertSame(
            2,
            $this->eventDate->getFiles()->count()
        );
    }

    #[Test]
    public function isBookableReturnsBookable(): void
    {
        $eventDate = new EventDate();

        self::assertFalse(
            $eventDate->isBookable()
        );

        ObjectAccess::setProperty($eventDate, 'bookable', true);

        self::assertTrue(
            $eventDate->isBookable()
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
    public function isPriceCategorizedReturnsCategorized(): void
    {
        $eventDate = new EventDate();

        self::assertFalse(
            $eventDate->isPriceCategorized()
        );

        ObjectAccess::setProperty($eventDate, 'priceCategorized', true);

        self::assertTrue(
            $eventDate->isPriceCategorized()
        );
    }

    #[Test]
    public function isHandleSeatsReturnsHandleSeats(): void
    {
        $eventDate = new EventDate();

        self::assertFalse(
            $eventDate->isHandleSeats()
        );

        ObjectAccess::setProperty($eventDate, 'handleSeats', true);

        self::assertTrue(
            $eventDate->isHandleSeats()
        );
    }

    #[Test]
    public function isHandleSeatsInPriceCategoryReturnsHandleSeatsInPriceCategory(): void
    {

        $eventDate = new EventDate();

        self::assertFalse(
            $eventDate->isHandleSeatsInPriceCategory()
        );

        ObjectAccess::setProperty($eventDate, 'handleSeatsInPriceCategory', true);

        self::assertTrue(
            $eventDate->isHandleSeatsInPriceCategory()
        );
    }

    #[Test]
    public function getSeatsNumberReturnsZeroIfHandleSeatsIsFalse()
    {
        $eventDate = new EventDate();

        self::assertSame(
            0,
            $this->eventDate->getSeatsNumber()
        );

        ObjectAccess::setProperty($eventDate, 'seatsNumber', 15);

        self::assertSame(
            0,
            $this->eventDate->getSeatsNumber()
        );
    }

    #[Test]
    public function getSeatsNumberReturnsInitialValueForSeatsNumberIfHandleSeatsIsTrue()
    {
        $eventDate = new EventDate();

        ObjectAccess::setProperty($eventDate, 'handleSeats', true);

        self::assertSame(
            0,
            $eventDate->getSeatsNumber()
        );
    }

    #[Test]
    public function setSeatsNumberSetsSeatsNumber()
    {
        $eventDate = new EventDate();

        ObjectAccess::setProperty($eventDate, 'seatsNumber', 15);

        self::assertSame(
            0,
            $eventDate->getSeatsNumber()
        );

        ObjectAccess::setProperty($eventDate, 'handleSeats', true);

        self::assertSame(
            15,
            $eventDate->getSeatsNumber()
        );
    }

    #[Test]
    public function getSeatsTakenReturnsZeroIfHandleSeatsIsFalse()
    {
        $eventDate = new EventDate();

        self::assertSame(
            0,
            $eventDate->getSeatsTaken()
        );

        ObjectAccess::setProperty($eventDate, 'seatsNumber', 15);

        self::assertSame(
            0,
            $eventDate->getSeatsTaken()
        );
    }

    #[Test]
    public function getSeatsTakenReturnsInitialValueForSeatsTakenIfHandleSeatsIsTrue()
    {
        $eventDate = new EventDate();

        ObjectAccess::setProperty($eventDate, 'handleSeats', true);

        self::assertSame(
            0,
            $eventDate->getSeatsTaken()
        );
    }

    #[Test]
    public function setSeatsTakenSetsSeatsTaken()
    {
        $eventDate = new EventDate();

        ObjectAccess::setProperty($eventDate, 'seatsTaken', 15);

        self::assertSame(
            0,
            $eventDate->getSeatsTaken()
        );

        ObjectAccess::setProperty($eventDate, 'handleSeats', true);

        self::assertSame(
            15,
            $eventDate->getSeatsTaken()
        );
    }

    #[Test]
    public function getSeatsAvailableReturnsZeroIfHandleSeatsIsFalse()
    {
        $eventDate = new EventDate();

        self::assertSame(
            0,
            $eventDate->getSeatsAvailable()
        );

        ObjectAccess::setProperty($eventDate, 'seatsNumber', 15);

        self::assertSame(
            0,
            $eventDate->getSeatsAvailable()
        );
    }

    #[Test]
    public function getSeatsAvailableReturnsDifferenceOfInitialValueForSeatsNumberAndSeatsTakenIfHandleSeatsIsTrue()
    {
        $eventDate = new EventDate();

        ObjectAccess::setProperty($eventDate, 'handleSeats', true);

        self::assertSame(
            0,
            $eventDate->getSeatsAvailable()
        );
    }

    #[Test]
    public function getSeatsAvailableDifferenceOfValueForSeatsNumberAndSeatsTakenIfHandleSeatsIsTrue()
    {
        $eventDate = new EventDate();

        ObjectAccess::setProperty($eventDate, 'seatsNumber', 30);
        ObjectAccess::setProperty($eventDate, 'seatsTaken', 13);

        self::assertSame(
            0,
            $eventDate->getSeatsAvailable()
        );

        ObjectAccess::setProperty($eventDate, 'handleSeats', true);

        self::assertSame(
            17,
            $eventDate->getSeatsAvailable()
        );
    }

    #[TEST]
    public function isAvailableReturnsFalseIfBookableIsFalse()
    {
        $eventDate = new EventDate();

        ObjectAccess::setProperty($eventDate, 'handleSeats', true);
        ObjectAccess::setProperty($eventDate, 'seatsNumber', 15);
        ObjectAccess::setProperty($eventDate, 'bookable', false);

        self::assertFalse(
            $eventDate->isAvailable()
        );

        ObjectAccess::setProperty($eventDate, 'bookable', true);

        self::assertTrue(
            $eventDate->isAvailable()
        );
    }

    #[TEST]
    public function isAvailableReturnsTrueIfIsBookableAndHandleSeatsIsFalse()
    {
        $eventDate = new EventDate();

        ObjectAccess::setProperty($eventDate, 'bookable', true);
        ObjectAccess::setProperty($eventDate, 'handleSeats', false);

        self::assertTrue(
            $eventDate->isAvailable()
        );

        ObjectAccess::setProperty($eventDate, 'handleSeats', true);

        self::assertFalse(
            $eventDate->isAvailable()
        );
    }

    #[TEST]
    public function isAvailableReturnsTrueIfIsBookableAndHandleSeatsIsTrueAndNumberOfSeatsIsGreaterThanZero()
    {
        $eventDate = new EventDate();

        ObjectAccess::setProperty($eventDate, 'bookable', true);
        ObjectAccess::setProperty($eventDate, 'handleSeats', true);
        ObjectAccess::setProperty($eventDate, 'seatsNumber', 2);

        self::assertTrue(
            $eventDate->isAvailable()
        );

        ObjectAccess::setProperty($eventDate, 'seatsTaken', 1);

        self::assertTrue(
            $eventDate->isAvailable()
        );

        ObjectAccess::setProperty($eventDate, 'seatsTaken', 2);

        self::assertFalse(
            $eventDate->isAvailable()
        );
    }

    #[TEST]
    public function isAvailableReturnsFalseIfIsBookableAndHandleSeatsIsTrueAndNumberOfSeatsIsLowerOrEqualToZero()
    {
        $eventDate = new EventDate();

        ObjectAccess::setProperty($eventDate, 'bookable', true);
        ObjectAccess::setProperty($eventDate, 'handleSeats', true);
        ObjectAccess::setProperty($eventDate, 'seatsNumber', 0);

        self::assertFalse(
            $eventDate->isAvailable()
        );

        ObjectAccess::setProperty($eventDate, 'seatsTaken', 1);

        self::assertFalse(
            $eventDate->isAvailable()
        );
    }
}
