<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Tests\Unit\Domain\Model;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use DateTime;
use Extcode\CartEvents\Domain\Model\AbstractEventDate;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversClass(AbstractEventDate::class)]
class AbstractEventDateTest extends UnitTestCase
{
    protected $eventDate;

    protected function setUp(): void
    {
        $this->eventDate = new class extends AbstractEventDate {};
    }

    protected function tearDown(): void
    {
        unset($this->eventDate);
    }

    #[Test]
    public function getBeginReturnsInitialValueNull(): void
    {
        self::assertNull(
            $this->eventDate->getBegin()
        );
    }

    #[Test]
    public function setBeginSetsBegin(): void
    {
        $dateString = '2024-09-01 15:43:38';
        $format = 'Y-m-d H:i:s';
        $dateTime = DateTime::createFromFormat($format, $dateString);

        $this->eventDate->setBegin($dateTime);

        self::assertSame(
            $dateTime,
            $this->eventDate->getBegin()
        );
    }

    #[Test]
    public function getEndReturnsInitialValueNull(): void
    {
        self::assertNull(
            $this->eventDate->getEnd()
        );
    }

    #[Test]
    public function setEndSetsEnd(): void
    {
        $dateString = '2024-09-01 20:01:01';
        $format = 'Y-m-d H:i:s';
        $dateTime = DateTime::createFromFormat($format, $dateString);
        $this->eventDate->setEnd($dateTime);

        self::assertSame(
            $dateTime,
            $this->eventDate->getEnd()
        );
    }

    #[Test]
    public function getNoteReturnsInitialValueForNote(): void
    {
        self::assertSame(
            '',
            $this->eventDate->getNote()
        );
    }

    #[Test]
    public function setNoteSetsNote(): void
    {
        $this->eventDate->setNote('Note');

        self::assertSame(
            'Note',
            $this->eventDate->getNote()
        );
    }
}
