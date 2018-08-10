<?php
declare(strict_types=1);

namespace Extcode\CartEvents\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Event extends AbstractEntity
{
    /**
     * SKU
     *
     * @var string
     * @validate NotEmpty
     */
    protected $sku = '';

    /**
     * Title
     *
     * @var string
     * @validate NotEmpty
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
     * Slots
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Extcode\CartEvents\Domain\Model\Slot>
     * @cascade remove
     */
    protected $slots;

    /**
     * Related Events
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Extcode\CartEvents\Domain\Model\Event>
     * @lazy
     */
    protected $relatedEvents = null;

    /**
     * Related Events (from)
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Extcode\CartEvents\Domain\Model\Event>
     * @lazy
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
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     */
    public function setSku(string $sku)
    {
        $this->sku = $sku;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTeaser(): string
    {
        return $this->teaser;
    }

    /**
     * @param string $teaser
     */
    public function setTeaser(string $teaser)
    {
        $this->teaser = $teaser;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getAudience(): string
    {
        return $this->audience;
    }

    /**
     * @param string $audience
     */
    public function setAudience(string $audience)
    {
        $this->audience = $audience;
    }

    /**
     * Returns the Images
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $images
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
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * Returns the Files
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $files
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Sets the Files
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getSlots()
    {
        return $this->slots;
    }

    /**
     * return \Extcode\CartEvents\Domain\Model\Slot
     */
    public function getFirstSlot()
    {
        return $this->getSlots()->current();
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $slots
     */
    public function setSlots(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $slots)
    {
        $this->slots = $slots;
    }

    /**
     * Adds a Related Event
     *
     * @param \Extcode\CartEvents\Domain\Model\Event $relatedEvent
     */
    public function addRelatedEvent(\Extcode\CartEvents\Domain\Model\Event $relatedEvent)
    {
        $this->relatedEvents->attach($relatedEvent);
    }

    /**
     * Removes a Related Event
     *
     * @param \Extcode\CartEvents\Domain\Model\Event $relatedEventToRemove
     */
    public function removeRelatedEvent(\Extcode\CartEvents\Domain\Model\Event $relatedEventToRemove)
    {
        $this->relatedEvents->detach($relatedEventToRemove);
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
     */
    public function setRelatedEvents(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $relatedEvents)
    {
        $this->relatedEvents = $relatedEvents;
    }

    /**
     * Adds a Related Event (from)
     *
     * @param \Extcode\CartEvents\Domain\Model\Event $relatedEventFrom
     */
    public function addRelatedEventFrom(\Extcode\CartEvents\Domain\Model\Event $relatedEventFrom)
    {
        $this->relatedEventsFrom->attach($relatedEventFrom);
    }

    /**
     * Removes a Related Event (from)
     *
     * @param \Extcode\CartEvents\Domain\Model\Event $relatedEventFromToRemove
     */
    public function removeRelatedEventFrom(\Extcode\CartEvents\Domain\Model\Event $relatedEventFromToRemove)
    {
        $this->relatedEventsFrom->detach($relatedEventFromToRemove);
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
     */
    public function setRelatedEventsFrom(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $relatedEventsFrom)
    {
        $this->relatedEventsFrom = $relatedEventsFrom;
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
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories
     */
    public function setCategories(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $tags
     */
    public function setTags(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return int
     */
    public function getTaxClassId(): int
    {
        return $this->taxClassId;
    }

    /**
     * @param int $taxClassId
     */
    public function setTaxClassId(int $taxClassId)
    {
        $this->taxClassId = $taxClassId;
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
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
    }
}
