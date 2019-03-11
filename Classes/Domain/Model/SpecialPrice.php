<?php

namespace Extcode\CartEvents\Domain\Model;

class SpecialPrice extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Title
     *
     * @var string
     * @validate NotEmpty
     */
    protected $title = '';

    /**
     * Price
     *
     * @var float
     * @validate NotEmpty
     */
    protected $price = 0.0;

    /**
     * Frontend User Group
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup
     */
    protected $frontendUserGroup;

    /**
     * Returns the Title
     *
     * @return string
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * Sets the Title
     *
     * @param string $title
     * @return SpecialPrice
     */
    public function setTitle($title) : self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Returns the Price
     *
     * @return float $price
     */
    public function getPrice() : float
    {
        return $this->price;
    }

    /**
     * Sets the Price
     *
     * @param float $price
     * @return SpecialPrice
     */
    public function setPrice($price) : self
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Returns the Frontend User Group
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup
     */
    public function getFrontendUserGroup() : \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup
    {
        return $this->frontendUserGroup;
    }

    /**
     * Sets the Frontend User Group
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup $frontendUserGroup
     * @return SpecialPrice
     */
    public function setFrontendUserGroup($frontendUserGroup) : self
    {
        $this->setFrontendUserGroup = $frontendUserGroup;
        return $this;
    }
}
