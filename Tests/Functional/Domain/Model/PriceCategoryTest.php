<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Tests\Functional\Domain\Model;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\CartEvents\Domain\Model\PriceCategory;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PriceCategory::class)]
class PriceCategoryTest extends AbstractSpecialPrice
{
    protected function setUp(): void
    {
        $this->price = 13.44;
        $this->subject = new PriceCategory();

        parent::setUp();
    }

    protected function tearDown(): void
    {
        unset($this->subject);

        parent::tearDown();
    }
}
