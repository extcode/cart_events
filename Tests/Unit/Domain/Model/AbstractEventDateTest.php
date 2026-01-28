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
use Extcode\CartEvents\Tests\ObjectAccess;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversClass(AbstractEventDate::class)]
class AbstractEventDateTest extends UnitTestCase
{
    protected bool $resetSingletonInstances = true;

    protected AbstractEventDate $eventDate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventDate = new class extends AbstractEventDate {};
    }

    #[Test]
    public function getBeginReturnsBegin(): void
    {
        self::assertNull(
            $this->eventDate->getBegin()
        );

        $dateString = '2024-09-01 15:43:38';
        $format = 'Y-m-d H:i:s';
        $begin = DateTime::createFromFormat($format, $dateString);

        ObjectAccess::setProperty($this->eventDate, 'begin', $begin);

        self::assertSame(
            $begin,
            $this->eventDate->getBegin()
        );
    }

    #[Test]
    public function getEndReturnsEnd(): void
    {
        self::assertNull(
            $this->eventDate->getEnd()
        );

        $dateString = '2024-09-01 20:01:01';
        $format = 'Y-m-d H:i:s';
        $end = DateTime::createFromFormat($format, $dateString);

        ObjectAccess::setProperty($this->eventDate, 'end', $end);

        self::assertSame(
            $end,
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

        ObjectAccess::setProperty($this->eventDate, 'note', 'note');

        self::assertSame(
            'note',
            $this->eventDate->getNote()
        );
    }
}
