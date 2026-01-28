<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Tests\Unit\Domain\Model;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\Cart\Domain\Model\FrontendUserGroup;
use Extcode\CartEvents\Domain\Model\SpecialPrice;
use Extcode\CartEvents\Tests\ObjectAccess;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversClass(SpecialPrice::class)]
class SpecialPriceTest extends UnitTestCase
{
    protected SpecialPrice $specialPrice;

    protected function setUp(): void
    {
        parent::setUp();

        $this->specialPrice = new SpecialPrice();
    }

    protected function tearDown(): void
    {
        unset($this->specialPrice);

        parent::tearDown();
    }

    #[Test]
    public function getTitleReturnsTitle(): void
    {
        $specialPrice = new SpecialPrice();

        self::assertSame(
            '',
            $specialPrice->getTitle()
        );

        ObjectAccess::setProperty($specialPrice, 'title', 'title');

        self::assertSame(
            'title',
            $specialPrice->getTitle()
        );
    }

    #[Test]
    public function getPriceReturnsPrice(): void
    {
        $specialPrice = new SpecialPrice();

        self::assertSame(
            0.0,
            $specialPrice->getPrice()
        );

        ObjectAccess::setProperty($specialPrice, 'price', 82.36);

        self::assertSame(
            82.36,
            $specialPrice->getPrice()
        );
    }

    #[Test]
    public function getFrontendUserGroupReturnsInitialValueNull(): void
    {
        $specialPrice = new SpecialPrice();

        self::assertNull(
            $this->specialPrice->getFrontendUserGroup()
        );

        $frontendUserGroup = self::createStub(
            FrontendUserGroup::class
        );
        ObjectAccess::setProperty($specialPrice, 'frontendUserGroup', $frontendUserGroup);

        self::assertSame(
            $frontendUserGroup,
            $specialPrice->getFrontendUserGroup()
        );
    }
}
