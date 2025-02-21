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
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversClass(SpecialPrice::class)]
class SpecialPriceTest extends UnitTestCase
{
    protected SpecialPrice $specialPrice;

    protected function setUp(): void
    {
        $this->specialPrice = new SpecialPrice();
    }

    protected function tearDown(): void
    {
        unset($this->specialPrice);
    }

    #[Test]
    public function getTitleReturnsInitialValueForTitle(): void
    {
        self::assertSame(
            '',
            $this->specialPrice->getTitle()
        );
    }

    #[Test]
    public function setTitleSetsTitle(): void
    {
        $this->specialPrice->setTitle('Title');

        self::assertSame(
            'Title',
            $this->specialPrice->getTitle()
        );
    }

    #[Test]
    public function getPriceReturnsInitialValueForPrice(): void
    {
        self::assertSame(
            0.0,
            $this->specialPrice->getPrice()
        );
    }

    #[Test]
    public function setPriceSetsPrice(): void
    {
        $this->specialPrice->setPrice(19.99);

        self::assertSame(
            19.99,
            $this->specialPrice->getPrice()
        );
    }

    #[Test]
    public function getFrontendUserGroupReturnsInitialValueNull(): void
    {
        self::assertNull(
            $this->specialPrice->getFrontendUserGroup()
        );
    }

    #[Test]
    public function setFrontendUserGroupSetsFrontendUserGroup(): void
    {
        $frontendUserGroup = self::createStub(
            FrontendUserGroup::class
        );
        $this->specialPrice->setFrontendUserGroup($frontendUserGroup);

        self::assertSame(
            $frontendUserGroup,
            $this->specialPrice->getFrontendUserGroup()
        );
    }
}
