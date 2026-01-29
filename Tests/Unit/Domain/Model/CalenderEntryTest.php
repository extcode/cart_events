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
use Extcode\CartEvents\Domain\Model\CalendarEntry;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversClass(CalendarEntry::class)]
class CalenderEntryTest extends UnitTestCase
{
    protected CalendarEntry $calendarEntry;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calendarEntry = new CalendarEntry();
    }

    #[Test]
    public function calendarEntryExtendsAbstractEventDate(): void
    {
        self::assertInstanceOf(AbstractEventDate::class, $this->calendarEntry);
    }
}
