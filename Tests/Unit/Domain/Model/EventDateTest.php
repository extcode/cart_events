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
}
