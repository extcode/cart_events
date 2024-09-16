<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Domain\Model;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\Cart\Domain\Model\Product\CategoryTrait;
use Extcode\Cart\Domain\Model\Product\TagTrait;
use TYPO3\CMS\Extbase\Annotation\ORM\Cascade;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Event extends AbstractEntity
{
    use CategoryTrait;
    use TagTrait;

    protected bool $virtualProduct = true;

    protected ?string $formDefinition = null;

    #[Validate(['validator' => 'NotEmpty'])]
    protected string $sku = '';

    #[Validate(['validator' => 'NotEmpty'])]
    protected string $title = '';

    protected string $teaser = '';

    protected string $description = '';

    protected string $audience = '';

    /**
     * @var ObjectStorage<FileReference>
     */
    protected ObjectStorage $images;

    /**
     * @var ObjectStorage<FileReference>
     */
    protected ObjectStorage $files;

    /**
     * @var ObjectStorage<EventDate>
     */
    #[Cascade(['value' => 'remove'])]
    protected ObjectStorage $eventDates;

    /**
     * @var ObjectStorage<Event>
     */
    #[Lazy]
    protected ObjectStorage $relatedEvents;

    /**
     * @var ObjectStorage<Event>
     */
    #[Lazy]
    protected ObjectStorage $relatedEventsFrom;

    protected int $taxClassId = 1;

    protected string $metaDescription = '';

    public function __construct() {}

    public function isVirtualProduct(): bool
    {
        return $this->virtualProduct;
    }

    public function setVirtualProduct(bool $virtualProduct): void
    {
        $this->virtualProduct = $virtualProduct;
    }

    public function getFormDefinition(): ?string
    {
        return $this->formDefinition;
    }

    public function setFormDefinition(string $formDefinition): void
    {
        $this->formDefinition = $formDefinition;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTeaser(): string
    {
        return $this->teaser;
    }

    public function setTeaser(string $teaser): void
    {
        $this->teaser = $teaser;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getAudience(): string
    {
        return $this->audience;
    }

    public function setAudience(string $audience): void
    {
        $this->audience = $audience;
    }

    public function getImages(): ?ObjectStorage
    {
        return $this->images;
    }

    public function getFirstImage(): ?FileReference
    {
        if (!$this->getImages()) {
            return null;
        }
        $images = $this->getImages()->toArray();
        return array_shift($images);
    }

    public function setImages(ObjectStorage $images): void
    {
        $this->images = $images;
    }

    public function getFiles(): ?ObjectStorage
    {
        return $this->files;
    }

    public function setFiles(ObjectStorage $files): void
    {
        $this->files = $files;
    }

    /**
     * @return ObjectStorage<EventDate>
     */
    public function getEventDates(): ?ObjectStorage
    {
        return $this->eventDates;
    }

    public function getFirstEventDate(): ?EventDate
    {
        if (!$this->getEventDates()) {
            return null;
        }
        return $this->getEventDates()->current();
    }

    public function setEventDates(ObjectStorage $eventDates): void
    {
        $this->eventDates = $eventDates;
    }

    public function addRelatedEvent(self $relatedEvent): void
    {
        $this->relatedEvents->attach($relatedEvent);
    }

    public function removeRelatedEvent(self $relatedEvent): void
    {
        $this->relatedEvents->detach($relatedEvent);
    }

    /**
     * @return ObjectStorage<Event>
     */
    public function getRelatedEvents(): ?ObjectStorage
    {
        return $this->relatedEvents;
    }

    public function setRelatedEvents(ObjectStorage $relatedEvents): void
    {
        $this->relatedEvents = $relatedEvents;
    }

    public function addRelatedEventFrom(self $relatedEventFrom): void
    {
        $this->relatedEventsFrom->attach($relatedEventFrom);
    }

    public function removeRelatedEventFrom(self $relatedEventFrom): void
    {
        $this->relatedEventsFrom->detach($relatedEventFrom);
    }

    /**
     * @return ObjectStorage<Event>
     */
    public function getRelatedEventsFrom(): ?ObjectStorage
    {
        return $this->relatedEventsFrom;
    }

    public function setRelatedEventsFrom(ObjectStorage $relatedEventsFrom): void
    {
        $this->relatedEventsFrom = $relatedEventsFrom;
    }

    public function getTaxClassId(): int
    {
        return $this->taxClassId;
    }

    public function setTaxClassId(int $taxClassId): void
    {
        $this->taxClassId = $taxClassId;
    }

    public function getMetaDescription(): string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(string $metaDescription): void
    {
        $this->metaDescription = $metaDescription;
    }
}
