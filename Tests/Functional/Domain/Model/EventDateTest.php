<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Tests\Functional\Domain\Model;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\CartEvents\Domain\Model\EventDate;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(EventDate::class)]
class EventDateTest extends AbstractSpecialPrice
{
    protected function setUp(): void
    {
        $this->price = 17.49;

        $this->subject = new EventDate();
        $this->subject->setPrice($this->price);
    }
}
