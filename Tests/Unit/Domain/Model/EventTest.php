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
use Extcode\CartEvents\Domain\Model\EventDate;
use Extcode\CartEvents\Tests\ObjectAccess;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversClass(Event::class)]
class EventTest extends UnitTestCase
{
    protected Event $event;

    protected function setUp(): void
    {
        parent::setUp();

        $this->event = new Event();
    }

    protected function tearDown(): void
    {
        unset($this->event);

        parent::tearDown();
    }

    #[Test]
    public function isVirtualProductReturnsInitialValueForVirtualProduct(): void
    {
        $event = new Event();

        self::assertTrue(
            $event->isVirtualProduct()
        );

        ObjectAccess::setProperty($event, 'virtualProduct', false);

        self::assertFalse(
            $event->isVirtualProduct()
        );
    }

    #[Test]
    public function getFormDefinitionReturnsFormDefinition(): void
    {
        $event = new Event();

        self::assertNull(
            $event->getFormDefinition()
        );

        ObjectAccess::setProperty($event, 'formDefinition', 'EXT:cart_events/Resources/Private/Forms/test-form.form.yaml');

        self::assertSame(
            'EXT:cart_events/Resources/Private/Forms/test-form.form.yaml',
            $event->getFormDefinition()
        );
    }

    #[Test]
    public function getSkuReturnsSku(): void
    {
        $event = new Event();

        self::assertSame(
            '',
            $this->event->getSku()
        );

        ObjectAccess::setProperty($event, 'sku', 'sku');

        self::assertSame(
            'sku',
            $event->getSku()
        );
    }

    #[Test]
    public function getTitleReturnsTitle(): void
    {
        $event = new Event();

        self::assertSame(
            '',
            $this->event->getTitle()
        );

        ObjectAccess::setProperty($event, 'title', 'title');

        self::assertSame(
            'title',
            $event->getTitle()
        );
    }

    #[Test]
    public function getTeaserReturnsTeaser(): void
    {
        $event = new Event();

        self::assertSame(
            '',
            $this->event->getTeaser()
        );

        ObjectAccess::setProperty($event, 'teaser', 'teaser');

        self::assertSame(
            'teaser',
            $event->getTeaser()
        );
    }

    #[Test]
    public function getDescriptionReturnsDescription(): void
    {
        $event = new Event();

        self::assertSame(
            '',
            $this->event->getDescription()
        );

        ObjectAccess::setProperty($event, 'description', 'description');

        self::assertSame(
            'description',
            $event->getDescription()
        );
    }

    #[Test]
    public function getAudienceReturnsAudience(): void
    {
        $event = new Event();

        self::assertSame(
            '',
            $this->event->getAudience()
        );

        ObjectAccess::setProperty($event, 'audience', 'audience');

        self::assertSame(
            'audience',
            $event->getAudience()
        );
    }

    #[Test]
    public function getImagesReturnsImages(): void
    {
        $images = $this->event->getImages();

        self::assertSame(
            $images,
            $this->event->getImages()
        );

        self::assertSame(
            0,
            $this->event->getImages()->count()
        );

        $image1 = self::createStub(FileReference::class);
        $images->attach($image1);
        $image2 = self::createStub(FileReference::class);
        $images->attach($image2);

        self::assertSame(
            $images,
            $this->event->getImages()
        );

        self::assertSame(
            2,
            $this->event->getImages()->count()
        );
    }

    #[Test]
    public function getFirstImageReturnsFirstImage(): void
    {
        $images = $this->event->getImages();

        self::assertNull(
            $this->event->getFirstImage()
        );

        $image1 = self::createStub(FileReference::class);
        $images->attach($image1);
        $image2 = self::createStub(FileReference::class);
        $images->attach($image2);

        self::assertSame(
            $image1,
            $this->event->getFirstImage()
        );
    }

    #[Test]
    public function getFilesReturnsFiles(): void
    {
        $files = $this->event->getFiles();

        self::assertSame(
            $files,
            $this->event->getFiles()
        );

        self::assertSame(
            0,
            $this->event->getFiles()->count()
        );

        $file1 = self::createStub(FileReference::class);
        $files->attach($file1);
        $file2 = self::createStub(FileReference::class);
        $files->attach($file2);

        self::assertSame(
            $files,
            $this->event->getFiles()
        );

        self::assertSame(
            2,
            $this->event->getFiles()->count()
        );
    }

    #[Test]
    public function getEventDatesReturnsEventDates(): void
    {
        $eventDates = $this->event->getEventDates();

        self::assertSame(
            $eventDates,
            $this->event->getEventDates()
        );

        self::assertSame(
            0,
            $this->event->getEventDates()->count()
        );

        $eventDate1 = self::createStub(FileReference::class);
        $eventDates->attach($eventDate1);
        $eventDate2 = self::createStub(FileReference::class);
        $eventDates->attach($eventDate2);

        self::assertSame(
            $eventDates,
            $this->event->getEventDates()
        );

        self::assertSame(
            2,
            $this->event->getEventDates()->count()
        );
    }

    #[Test]
    public function getFirstEventDateReturnsFirstEventDate(): void
    {
        $eventDates = $this->event->getEventDates();

        self::assertNull(
            $this->event->getFirstEventDate()
        );

        $eventDate1 = self::createStub(EventDate::class);
        $eventDates->attach($eventDate1);
        $eventDate2 = self::createStub(EventDate::class);
        $eventDates->attach($eventDate2);

        self::assertSame(
            $eventDate1,
            $this->event->getFirstEventDate()
        );
    }

    // todo: relatedEvents

    #[Test]
    public function getTaxClassIdReturnsTaxClassId(): void
    {
        $event = new Event();

        self::assertSame(
            1,
            $this->event->getTaxClassId()
        );

        ObjectAccess::setProperty($event, 'taxClassId', 7);

        self::assertSame(
            7,
            $event->getTaxClassId()
        );
    }

    #[Test]
    public function getMetaDescriptionReturnsMetaDescription(): void
    {
        $event = new Event();

        self::assertSame(
            '',
            $this->event->getMetaDescription()
        );

        ObjectAccess::setProperty($event, 'metaDescription', 'meta description');

        self::assertSame(
            'meta description',
            $event->getMetaDescription()
        );
    }
}
