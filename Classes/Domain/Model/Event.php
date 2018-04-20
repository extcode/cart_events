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
     * Slots
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Extcode\CartEvents\Domain\Model\Slot>
     * @cascade remove
     */
    protected $slots;

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
