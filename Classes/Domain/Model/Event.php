<?php
declare(strict_types=1);

namespace Extcode\CartEvents\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Event extends AbstractEntity
{
    /**
     * @var bool
     */
    protected $virtualProduct = true;

    /**
     * SKU
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $sku = '';

    /**
     * Title
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $title = '';

    /**
     * Teaser
     *
     * @var string
     */
    protected $teaser = '';

    /**
     * Description
     *
     * @var string
     */
    protected $description = '';

    /**
     * Audience
     *
     * @var string
     */
    protected $audience = '';

    /**
     * Images
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected $images;

    /**
     * Files
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected $files;

    /**
     * EventDates
     *
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Extcode\CartEvents\Domain\Model\EventDate>
     */
    protected $eventDates;

    /**
     * Related Events
     *
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Extcode\CartEvents\Domain\Model\Event>
     */
    protected $relatedEvents = null;

    /**
     * Related Events (from)
     *
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Extcode\CartEvents\Domain\Model\Event>
     */
    protected $relatedEventsFrom;

    /**
     * Main Category
     *
     * @var \Extcode\CartEvents\Domain\Model\Category
     */
    protected $category;

    /**
     * Associated Categories
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Extcode\CartEvents\Domain\Model\Category>
     */
    protected $categories;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Extcode\Cart\Domain\Model\Tag>
     */
    protected $tags;

    /**
     * TaxClass Id
     *
     * @var int
     */
    protected $taxClassId = 1;

    /**
     * Meta description
     *
     * @var string
     */
    protected $metaDescription = '';

    /**
     * @return bool
     */
    public function isVirtualProduct(): bool
    {
        return $this->virtualProduct;
    }

    /**
     * @param bool $virtualProduct
     * @return Event
     */
    public function setVirtualProduct(bool $virtualProduct) : self
    {
        $this->virtualProduct = $virtualProduct;
        return $this;
    }

    /**
     * @return string
     */
    public function getSku() : string
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     * @return Event
     */
    public function setSku(string $sku) : self
    {
        $this->sku = $sku;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Event
     */
    public function setTitle(string $title) : self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTeaser() : string
    {
        return $this->teaser;
    }

    /**
     * @param string $teaser
     * @return Event
     */
    public function setTeaser(string $teaser) : self
    {
        $this->teaser = $teaser;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Event
     */
    public function setDescription(string $description) : self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getAudience() : string
    {
        return $this->audience;
    }

    /**
     * @param string $audience
     * @return Event
     */
    public function setAudience(string $audience) : self
    {
        $this->audience = $audience;
        return $this;
    }

    /**
     * Returns the Images
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Returns the first Image
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $image
     */
    public function getFirstImage()
    {
        return array_shift($this->getImages()->toArray());
    }

    /**
     * Sets the Images
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $images
     * @return Event
     */
    public function setImages($images) : self
    {
        $this->images = $images;
        return $this;
    }

    /**
     * Returns the Files
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Sets the Files
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $files
     * @return Event
     */
    public function setFiles($files) : self
    {
        $this->files = $files;
        return $this;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Extcode\CartEvents\Domain\Model\EventDate>
     */
    public function getEventDates()
    {
        return $this->eventDates;
    }

    /**
     * return \Extcode\CartEvents\Domain\Model\EventDate
     */
    public function getFirstEventDate() : \Extcode\CartEvents\Domain\Model\EventDate
    {
        return $this->getEventDates()->current();
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $eventDates
     * @return Event
     */
    public function setEventDates(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $eventDates) : self
    {
        $this->eventDates = $eventDates;
        return $this;
    }

    /**
     * Adds a Related Event
     *
     * @param \Extcode\CartEvents\Domain\Model\Event $relatedEvent
     * @return Event
     */
    public function addRelatedEvent(self $relatedEvent) : self
    {
        $this->relatedEvents->attach($relatedEvent);
        return $this;
    }

    /**
     * Removes a Related Event
     *
     * @param \Extcode\CartEvents\Domain\Model\Event $relatedEventToRemove
     * @return Event
     */
    public function removeRelatedEvent(self $relatedEventToRemove) : self
    {
        $this->relatedEvents->detach($relatedEventToRemove);
        return $this;
    }

    /**
     * Returns the Related Events
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Extcode\CartEvents\Domain\Model\Event> $relatedEvent
     */
    public function getRelatedEvents()
    {
        return $this->relatedEvents;
    }

    /**
     * Sets the Related Events
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Extcode\CartEvents\Domain\Model\Event> $relatedEvents
     * @return Event
     */
    public function setRelatedEvents(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $relatedEvents) : self
    {
        $this->relatedEvents = $relatedEvents;
        return $this;
    }

    /**
     * Adds a Related Event (from)
     *
     * @param \Extcode\CartEvents\Domain\Model\Event $relatedEventFrom
     * @return Event
     */
    public function addRelatedEventFrom(self $relatedEventFrom) : self
    {
        $this->relatedEventsFrom->attach($relatedEventFrom);
        return $this;
    }

    /**
     * Removes a Related Event (from)
     *
     * @param \Extcode\CartEvents\Domain\Model\Event $relatedEventFromToRemove
     * @return Event
     */
    public function removeRelatedEventFrom(self $relatedEventFromToRemove) : self
    {
        $this->relatedEventsFrom->detach($relatedEventFromToRemove);
        return $this;
    }

    /**
     * Returns the Related Events (from)
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Extcode\CartEvents\Domain\Model\Event> $relatedEventFrom
     */
    public function getRelatedEventsFrom()
    {
        return $this->relatedEventsFrom;
    }

    /**
     * Sets the Related Events (from)
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Extcode\CartEvents\Domain\Model\Event> $relatedEventsFrom
     * @return Event
     */
    public function setRelatedEventsFrom(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $relatedEventsFrom) : self
    {
        $this->relatedEventsFrom = $relatedEventsFrom;
        return $this;
    }

    /**
     * @return \Extcode\CartEvents\Domain\Model\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param \Extcode\CartEvents\Domain\Model\Category $category
     * @return Event
     */
    public function setCategory(Category $category) : self
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getCategories() : \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->categories;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories
     * @return Event
     */
    public function setCategories(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories) : self
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getTags() : \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->tags;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $tags
     * @return Event
     */
    public function setTags(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $tags) : self
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @return int
     */
    public function getTaxClassId() : int
    {
        return $this->taxClassId;
    }

    /**
     * @param int $taxClassId
     * @return Event
     */
    public function setTaxClassId(int $taxClassId) : self
    {
        $this->taxClassId = $taxClassId;
        return $this;
    }

    /**
     * Returns MetaDescription
     *
     * @return string $metaDescription
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Sets MetaDescription
     *
     * @param string $metaDescription
     * @return Event
     */
    public function setMetaDescription($metaDescription) : self
    {
        $this->metaDescription = $metaDescription;
        return $this;
    }
}
