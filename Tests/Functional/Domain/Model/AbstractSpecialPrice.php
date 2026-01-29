<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Tests\Functional\Domain\Model;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\Cart\Domain\Model\FrontendUserGroup;
use Extcode\CartEvents\Domain\Model\EventDate;
use Extcode\CartEvents\Domain\Model\PriceCategory;
use Extcode\CartEvents\Domain\Model\SpecialPrice;
use Extcode\CartEvents\Tests\ObjectAccess;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\UserAspect;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

abstract class AbstractSpecialPrice extends FunctionalTestCase
{
    protected float $price;

    protected EventDate|PriceCategory $subject;

    protected function setUp(): void
    {
        parent::setUp();

        ObjectAccess::setProperty($this->subject, 'price', $this->price);
        ObjectAccess::setProperty($this->subject, 'specialPrices', new ObjectStorage());
    }

    #[Test]
    public function getBestSpecialPriceReturnsNullIfEventHasNoSpecialPrice(): void
    {
        self::assertNull(
            $this->subject->getBestSpecialPrice()
        );
    }

    #[Test]
    public function getBestSpecialPriceReturnsNullIfEventHasSpecialPriceButUserHasNoGroup(): void
    {
        $this->setUpFrontendUserAspect();

        $frontendUserGroup1 = self::createStub(FrontendUserGroup::class);
        $frontendUserGroup1->method('getUid')->willReturn(42);

        $specialPrices = $this->subject->getSpecialPrices();
        $specialPrice1 = $this->createSpecialPriceForFrontendUserGroup(10.00, $frontendUserGroup1);
        $specialPrices->attach($specialPrice1);

        self::assertNull(
            $this->subject->getBestSpecialPrice()
        );

        self::assertSame(
            $this->price,
            $this->subject->getBestPrice()
        );
    }

    #[Test]
    public function getBestSpecialPriceReturnsSpecialPriceIfEventHasASpecialPriceWithNoUserGroup(): void
    {
        $this->setUpFrontendUserAspect([]);

        $frontendUserGroup1 = self::createStub(FrontendUserGroup::class);
        $frontendUserGroup1->method('getUid')->willReturn(42);

        $specialPrices = $this->subject->getSpecialPrices();
        $specialPrice1 = $this->createSpecialPriceForFrontendUserGroup(10.00, $frontendUserGroup1);
        $specialPrices->attach($specialPrice1);

        self::assertNull(
            $this->subject->getBestSpecialPrice()
        );

        self::assertSame(
            $this->price,
            $this->subject->getBestPrice()
        );
    }

    #[Test]
    public function getBestSpecialPriceReturnsSpecialPriceIfEventHasASpecialPriceWithMatchingUserGroup(): void
    {
        $this->setUpFrontendUserAspect([42, 43]);

        $frontendUserGroup1 = self::createStub(FrontendUserGroup::class);
        $frontendUserGroup1->method('getUid')->willReturn(42);

        $specialPrices = $this->subject->getSpecialPrices();
        $specialPrice1 = $this->createSpecialPriceForFrontendUserGroup(10.00, $frontendUserGroup1);
        $specialPrices->attach($specialPrice1);

        self::assertSame(
            $specialPrice1,
            $this->subject->getBestSpecialPrice()
        );

        self::assertSame(
            10.00,
            $this->subject->getBestPrice()
        );
    }

    #[Test]
    public function getBestSpecialPriceReturnsBestSpecialPriceIfEventHasSpecialPricesWithMatchingUserGroup(): void
    {
        $this->setUpFrontendUserAspect([42, 43]);

        $frontendUserGroup1 = self::createStub(FrontendUserGroup::class);
        $frontendUserGroup1->method('getUid')->willReturn(42);
        $frontendUserGroup2 = self::createStub(FrontendUserGroup::class);
        $frontendUserGroup2->method('getUid')->willReturn(43);

        $specialPrices = $this->subject->getSpecialPrices();
        $specialPrice1 = $this->createSpecialPriceForFrontendUserGroup(10.00, $frontendUserGroup1);
        $specialPrices->attach($specialPrice1);
        $specialPrice2 = $this->createSpecialPriceForFrontendUserGroup(8.00, $frontendUserGroup2);
        $specialPrices->attach($specialPrice2);

        self::assertSame(
            $specialPrice2,
            $this->subject->getBestSpecialPrice()
        );

        self::assertSame(
            8.00,
            $this->subject->getBestPrice()
        );
    }

    #[Test]
    public function getBestSpecialPriceReturnsBestSpecialPriceForUserGroupIfEventHasSpecialPricesWithMatchingUserGroup(): void
    {
        $this->setUpFrontendUserAspect([42, 43]);

        $frontendUserGroup1 = self::createStub(FrontendUserGroup::class);
        $frontendUserGroup1->method('getUid')->willReturn(42);
        $frontendUserGroup2 = self::createStub(FrontendUserGroup::class);
        $frontendUserGroup2->method('getUid')->willReturn(43);
        $frontendUserGroup3 = self::createStub(FrontendUserGroup::class);
        $frontendUserGroup3->method('getUid')->willReturn(44);

        $specialPrices = $this->subject->getSpecialPrices();
        $specialPrice1 = $this->createSpecialPriceForFrontendUserGroup(9.00, $frontendUserGroup1);
        $specialPrices->attach($specialPrice1);
        $specialPrice2 = $this->createSpecialPriceForFrontendUserGroup(11.00, $frontendUserGroup2);
        $specialPrices->attach($specialPrice2);
        $specialPrice3 = $this->createSpecialPriceForFrontendUserGroup(7.00, $frontendUserGroup3);
        $specialPrices->attach($specialPrice3);

        self::assertSame(
            $specialPrice1,
            $this->subject->getBestSpecialPrice()
        );

        self::assertSame(
            9.00,
            $this->subject->getBestPrice()
        );
    }

    #[Test]
    public function getBestSpecialPriceReturnsSpecialPriceIfEventHasASpecialPriceWithNotMatchingUserGroup(): void
    {
        $this->setUpFrontendUserAspect([41, 43]);

        $frontendUserGroup1 = self::createStub(FrontendUserGroup::class);
        $frontendUserGroup1->method('getUid')->willReturn(42);

        $specialPrices = $this->subject->getSpecialPrices();
        $specialPrice1 = $this->createSpecialPriceForFrontendUserGroup(10.00, $frontendUserGroup1);
        $specialPrices->attach($specialPrice1);

        self::assertNull(
            $this->subject->getBestSpecialPrice()
        );

        self::assertSame(
            $this->price,
            $this->subject->getBestPrice()
        );
    }

    #[Test]
    public function getBestPriceReturnsPriceIfSpecialPriceIsGreater(): void
    {
        $this->setUpFrontendUserAspect([42]);

        $frontendUserGroup1 = self::createStub(FrontendUserGroup::class);
        $frontendUserGroup1->method('getUid')->willReturn(42);

        $specialPrices = $this->subject->getSpecialPrices();
        $specialPrice1 = $this->createSpecialPriceForFrontendUserGroup(20.00, $frontendUserGroup1);
        $specialPrices->attach($specialPrice1);

        self::assertSame(
            $specialPrice1,
            $this->subject->getBestSpecialPrice()
        );

        self::assertSame(
            $this->price,
            $this->subject->getBestPrice()
        );
    }

    private function createSpecialPriceForFrontendUserGroup(float $price, FrontendUserGroup $frontendUserGroup): SpecialPrice
    {
        $specialPrice = new SpecialPrice();

        ObjectAccess::setProperty($specialPrice, 'price', $price);
        ObjectAccess::setProperty($specialPrice, 'frontendUserGroup', $frontendUserGroup);

        return $specialPrice;
    }

    private function setUpFrontendUserAspect(?array $groupIds = null): void
    {
        $userAspect = GeneralUtility::makeInstance(
            UserAspect::class,
            null,
            $groupIds
        );

        $context = GeneralUtility::makeInstance(Context::class);
        $context->setAspect(
            'frontend.user',
            $userAspect
        );
    }
}
