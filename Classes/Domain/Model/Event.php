<?php
declare(strict_types=1);
namespace Extcode\CartEvents\Domain\Model;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\Cart\Domain\Model\Tag;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Event extends AbstractEntity
{
    /**
     * @var bool
     */
    protected $virtualProduct = true;

    /**
     * @var string
     */
    protected $formDefinition;

    /**
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $sku = '';

    /**
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $teaser = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var string
     */
    protected $audience = '';

    /**
     * @var ObjectStorage<FileReference>
     */
    protected $images;

    /**
     * @var ObjectStorage<FileReference>
     */
    protected $files;

    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     * @var ObjectStorage<EventDate>
     */
    protected $eventDates;

    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var ObjectStorage<Event>
     */
    protected $relatedEvents;

    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var ObjectStorage<Event>
     */
    protected $relatedEventsFrom;

    /**
     * @var Category
     */
    protected $category;

    /**
     * @var ObjectStorage<Category>
     */
    protected $categories;

    /**
     * @var ObjectStorage<Tag>
     */
    protected $tags;

    /**
     * @var int
     */
    protected $taxClassId = 1;

    /**
     * @var string
     */
    protected $metaDescription = '';

    public function isVirtualProduct(): bool
    {
        return $this->virtualProduct;
    }

    public function setVirtualProduct(bool $virtualProduct): self
    {
        $this->virtualProduct = $virtualProduct;
        return $this;
    }

    public function getFormDefinition(): string
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

    public function setSku(string $sku): self
    {
        $this->sku = $sku;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getTeaser(): string
    {
        return $this->teaser;
    }

    public function setTeaser(string $teaser): self
    {
        $this->teaser = $teaser;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getAudience(): string
    {
        return $this->audience;
    }

    public function setAudience(string $audience): self
    {
        $this->audience = $audience;
        return $this;
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

    public function setImages(ObjectStorage $images): self
    {
        $this->images = $images;
        return $this;
    }

    public function getFiles(): ?ObjectStorage
    {
        return $this->files;
    }

    public function setFiles(ObjectStorage $files): self
    {
        $this->files = $files;
        return $this;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Extcode\CartEvents\Domain\Model\EventDate>
     */
    public function getEventDates(): ObjectStorage
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

    public function setEventDates(ObjectStorage $eventDates): self
    {
        $this->eventDates = $eventDates;
        return $this;
    }

    public function addRelatedEvent(self $relatedEvent): self
    {
        $this->relatedEvents->attach($relatedEvent);
        return $this;
    }

    public function removeRelatedEvent(self $relatedEvent): self
    {
        $this->relatedEvents->detach($relatedEvent);
        return $this;
    }

    /**
     * @return ObjectStorage<Event>
     */
    public function getRelatedEvents(): ?ObjectStorage
    {
        return $this->relatedEvents;
    }

    public function setRelatedEvents(ObjectStorage $relatedEvents): self
    {
        $this->relatedEvents = $relatedEvents;
        return $this;
    }

    public function addRelatedEventFrom(self $relatedEventFrom): self
    {
        $this->relatedEventsFrom->attach($relatedEventFrom);
        return $this;
    }

    public function removeRelatedEventFrom(self $relatedEventFrom): self
    {
        $this->relatedEventsFrom->detach($relatedEventFrom);
        return $this;
    }

    /**
     * @return ObjectStorage<Event>
     */
    public function getRelatedEventsFrom(): ?ObjectStorage
    {
        return $this->relatedEventsFrom;
    }

    public function setRelatedEventsFrom(ObjectStorage $relatedEventsFrom): self
    {
        $this->relatedEventsFrom = $relatedEventsFrom;
        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return ObjectStorage<Category>
     */
    public function getCategories(): ObjectStorage
    {
        return $this->categories;
    }

    public function setCategories(ObjectStorage $categories): self
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * @return ObjectStorage<Tag>
     */
    public function getTags(): ObjectStorage
    {
        return $this->tags;
    }

    public function setTags(ObjectStorage $tags): self
    {
        $this->tags = $tags;
        return $this;
    }

    public function getTaxClassId(): int
    {
        return $this->taxClassId;
    }

    public function setTaxClassId(int $taxClassId): self
    {
        $this->taxClassId = $taxClassId;
        return $this;
    }

    public function getMetaDescription(): string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;
        return $this;
    }
}
