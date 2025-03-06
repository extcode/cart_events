<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Tests\Unit\Domain\Model;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\CartEvents\Domain\Model\Event;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversClass(Event::class)]
class EventTest extends UnitTestCase
{
    protected Event $event;

    protected function setUp(): void
    {
        $this->event = new Event();
    }

    protected function tearDown(): void
    {
        unset($this->event);
    }

    #[Test]
    public function isVirtualProductReturnsInitialValueForVirtualProduct(): void
    {
        self::assertTrue(
            $this->event->isVirtualProduct()
        );
    }

    #[Test]
    public function setVirtualProductSetsVirtualProduct(): void
    {
        $this->event->setVirtualProduct(false);

        self::assertFalse(
            $this->event->isVirtualProduct()
        );
    }

    #[Test]
    public function getFormDefinitionReturnsInitialValueNull(): void
    {
        self::assertNull(
            $this->event->getFormDefinition()
        );
    }

    #[Test]
    public function setFormDefinitionSetsFormDefinition(): void
    {
        $this->event->setFormDefinition('EXT:cart_events/Resources/Private/Forms/test-form.form.yaml');

        self::assertSame(
            'EXT:cart_events/Resources/Private/Forms/test-form.form.yaml',
            $this->event->getFormDefinition()
        );
    }

    #[Test]
    public function getSkuReturnsInitialValueForSku(): void
    {
        self::assertSame(
            '',
            $this->event->getSku()
        );
    }

    #[Test]
    public function setSkuSetsSku(): void
    {
        $this->event->setSku('sku');

        self::assertSame(
            'sku',
            $this->event->getSku()
        );
    }

    #[Test]
    public function getTitleReturnsInitialValueForTitle(): void
    {
        self::assertSame(
            '',
            $this->event->getTitle()
        );
    }

    #[Test]
    public function setTitleSetsTitle(): void
    {
        $this->event->setTitle('Title');

        self::assertSame(
            'Title',
            $this->event->getTitle()
        );
    }

    #[Test]
    public function getTeaserReturnsInitialValueForTeaser(): void
    {
        self::assertSame(
            '',
            $this->event->getTeaser()
        );
    }

    #[Test]
    public function setTeaserSetsTeaser(): void
    {
        $this->event->setTeaser('Teaser');

        self::assertSame(
            'Teaser',
            $this->event->getTeaser()
        );
    }

    #[Test]
    public function getDescriptionReturnsInitialValueForDescription(): void
    {
        self::assertSame(
            '',
            $this->event->getDescription()
        );
    }

    #[Test]
    public function setDescriptionSetsDescription(): void
    {
        $this->event->setDescription('Description');

        self::assertSame(
            'Description',
            $this->event->getDescription()
        );
    }

    #[Test]
    public function getAudienceReturnsInitialValueForAudience(): void
    {
        self::assertSame(
            '',
            $this->event->getAudience()
        );
    }

    #[Test]
    public function setAudienceSetsAudience(): void
    {
        $this->event->setAudience('Audience');

        self::assertSame(
            'Audience',
            $this->event->getAudience()
        );
    }

    // todo: images, files, eventDates, relatedEvents, taxClassId,

    #[Test]
    public function getMetaDescriptionReturnsInitialValueForMetaDescription(): void
    {
        self::assertSame(
            '',
            $this->event->getMetaDescription()
        );
    }

    #[Test]
    public function setMetaDescriptionSetsMetaDescription(): void
    {
        $this->event->setMetaDescription('MetaDescription');

        self::assertSame(
            'MetaDescription',
            $this->event->getMetaDescription()
        );
    }
}
