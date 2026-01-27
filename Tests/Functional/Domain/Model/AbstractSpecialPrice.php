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

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    #[Test]
    public function getBestSpecialPriceReturnsNullIfEventHasNoSpecialPrice(): void
    {
        $objectStorage = new ObjectStorage();
        $this->subject->setSpecialPrices($objectStorage);

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

        $objectStorage = new ObjectStorage();
        $specialPrice1 = $this->createSpecialPriceForFrontendUserGroup(10.00, $frontendUserGroup1);
        $objectStorage->attach($specialPrice1);
        $this->subject->setSpecialPrices($objectStorage);

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

        $objectStorage = new ObjectStorage();
        $specialPrice1 = $this->createSpecialPriceForFrontendUserGroup(10.00, $frontendUserGroup1);
        $objectStorage->attach($specialPrice1);
        $this->subject->setSpecialPrices($objectStorage);

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

        $objectStorage = new ObjectStorage();
        $specialPrice1 = $this->createSpecialPriceForFrontendUserGroup(10.00, $frontendUserGroup1);
        $objectStorage->attach($specialPrice1);
        $this->subject->setSpecialPrices($objectStorage);

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

        $objectStorage = new ObjectStorage();
        $specialPrice1 = $this->createSpecialPriceForFrontendUserGroup(10.00, $frontendUserGroup1);
        $objectStorage->attach($specialPrice1);
        $specialPrice2 = $this->createSpecialPriceForFrontendUserGroup(8.00, $frontendUserGroup2);
        $objectStorage->attach($specialPrice2);
        $this->subject->setSpecialPrices($objectStorage);

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

        $objectStorage = new ObjectStorage();
        $specialPrice1 = $this->createSpecialPriceForFrontendUserGroup(9.00, $frontendUserGroup1);
        $objectStorage->attach($specialPrice1);
        $specialPrice2 = $this->createSpecialPriceForFrontendUserGroup(11.00, $frontendUserGroup2);
        $objectStorage->attach($specialPrice2);
        $specialPrice3 = $this->createSpecialPriceForFrontendUserGroup(7.00, $frontendUserGroup3);
        $objectStorage->attach($specialPrice3);
        $this->subject->setSpecialPrices($objectStorage);

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

        $objectStorage = new ObjectStorage();
        $specialPrice1 = $this->createSpecialPriceForFrontendUserGroup(10.00, $frontendUserGroup1);
        $objectStorage->attach($specialPrice1);
        $this->subject->setSpecialPrices($objectStorage);

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

        $objectStorage = new ObjectStorage();
        $specialPrice1 = $this->createSpecialPriceForFrontendUserGroup(20.00, $frontendUserGroup1);
        $objectStorage->attach($specialPrice1);
        $this->subject->setSpecialPrices($objectStorage);

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
        $specialPrice->setPrice($price);
        $specialPrice->setFrontendUserGroup($frontendUserGroup);

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
