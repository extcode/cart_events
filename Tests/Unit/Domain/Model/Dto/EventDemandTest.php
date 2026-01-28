<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Tests\Unit\Domain\Model\Dto;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\CartEvents\Domain\Model\Dto\EventDemand;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversClass(EventDemand::class)]
class EventDemandTest extends UnitTestCase
{
    protected EventDemand $eventDemand;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventDemand = new EventDemand();
    }

    #[Test]
    public function getSkuReturnsInitialValueForSku(): void
    {
        self::assertSame(
            '',
            $this->eventDemand->getSku()
        );
    }

    #[Test]
    public function setSkuSetsSku(): void
    {
        $this->eventDemand->setSku('sku');

        self::assertSame(
            'sku',
            $this->eventDemand->getSku()
        );
    }

    #[Test]
    public function getTitleReturnsInitialValueForTitle(): void
    {
        self::assertSame(
            '',
            $this->eventDemand->getTitle()
        );
    }

    #[Test]
    public function setTitleSetsTitle(): void
    {
        $this->eventDemand->setTitle('Title');

        self::assertSame(
            'Title',
            $this->eventDemand->getTitle()
        );
    }

    #[Test]
    public function getCategoriesReturnsInitialValueForCategories(): void
    {
        self::assertSame(
            [],
            $this->eventDemand->getCategories()
        );
    }

    #[Test]
    public function setCategoriesSetsCategories(): void
    {
        $this->eventDemand->setCategories([2, 3, 5, 7]);

        self::assertSame(
            [2, 3, 5, 7],
            $this->eventDemand->getCategories()
        );
    }

    #[Test]
    public function getOrderReturnsInitialValueForOrder(): void
    {
        self::assertSame(
            '',
            $this->eventDemand->getOrder()
        );
    }

    #[Test]
    public function setOrderSetsOrder(): void
    {
        $this->eventDemand->setOrder('Order');

        self::assertSame(
            'Order',
            $this->eventDemand->getOrder()
        );
    }

    #[Test]
    public function getLimitReturnsInitialValueForLimit(): void
    {
        self::assertSame(
            0,
            $this->eventDemand->getLimit()
        );
    }

    #[Test]
    public function setLimitSetsLimit(): void
    {
        $this->eventDemand->setLimit(10);

        self::assertSame(
            10,
            $this->eventDemand->getLimit()
        );
    }

    #[Test]
    public function getActionReturnsInitialValueForAction(): void
    {
        self::assertSame(
            '',
            $this->eventDemand->getAction()
        );
    }

    #[Test]
    public function setActionSetsAction(): void
    {
        $this->eventDemand->setAction('Action');

        self::assertSame(
            'Action',
            $this->eventDemand->getAction()
        );
    }

    #[Test]
    public function getClassReturnsInitialValueForClass(): void
    {
        self::assertSame(
            '',
            $this->eventDemand->getClass()
        );
    }

    #[Test]
    public function setClassSetsClass(): void
    {
        $this->eventDemand->setClass('Class');

        self::assertSame(
            'Class',
            $this->eventDemand->getClass()
        );
    }

    #[Test]
    public function setActionAndClassSetsActionAndClass(): void
    {
        $this->eventDemand->setActionAndClass('Action', 'Class');

        self::assertSame(
            'Action',
            $this->eventDemand->getAction()
        );
        self::assertSame(
            'Class',
            $this->eventDemand->getClass()
        );
    }
}
