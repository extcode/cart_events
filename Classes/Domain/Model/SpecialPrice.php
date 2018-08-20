<?php

namespace Extcode\CartEvents\Domain\Model;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Product SpecialPrice
 *
 * @author Daniel Lorenz <ext.cart@extco.de>
 */
class SpecialPrice extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Title
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $title = '';

    /**
     * Price
     *
     * @var float
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
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
     * @return null|\TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup
     */
    public function getFrontendUserGroup() : ?\TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup
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
