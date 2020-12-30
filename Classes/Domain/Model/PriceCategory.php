<?php
declare(strict_types=1);
namespace Extcode\CartEvents\Domain\Model;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class PriceCategory extends AbstractEntity
{

    /**
     * @var \Extcode\CartEvents\Domain\Model\EventDate
     */
    protected $eventDate = null;

    /**
     * SKU
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $sku;

    /**
     * Title
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $title = '';

    /**
     * @var float
     */
    protected $price = 0.0;

    /**
     * PriceCategory Special Price
     *
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Extcode\CartEvents\Domain\Model\SpecialPrice>
     */
    protected $specialPrices;

    /**
     * Number of seats
     *
     * @var int
     */
    protected $seatsNumber = 0;

    /**
     * Number of seats (taken)
     *
     * @var int
     */
    protected $seatsTaken = 0;

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @return EventDate
     */
    public function getEventDate(): EventDate
    {
        return $this->eventDate;
    }

    /**
     * @param EventDate $eventDate
     */
    public function setEventDate(EventDate $eventDate): void
    {
        $this->eventDate = $eventDate;
    }

    /**
     * @param string $sku
     */
    public function setSku(string $sku): void
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
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * Returns the Special Prices
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Extcode\CartEvents\Domain\Model\SpecialPrice>
     */
    public function getSpecialPrices() : \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->specialPrices;
    }

    /**
     * Adds a Special Price
     *
     * @param \Extcode\CartEvents\Domain\Model\SpecialPrice $specialPrice
     * @return EventDate
     */
    public function addSpecialPrice(\Extcode\CartEvents\Domain\Model\SpecialPrice $specialPrice) : self
    {
        $this->specialPrices->attach($specialPrice);
        return $this;
    }

    /**
     * Removes a Special Price
     *
     * @param \Extcode\CartEvents\Domain\Model\SpecialPrice $specialPriceToRemove
     * @return EventDate
     */
    public function removeSpecialPrice(\Extcode\CartEvents\Domain\Model\SpecialPrice $specialPriceToRemove) : self
    {
        $this->specialPrices->detach($specialPriceToRemove);
        return $this;
    }

    /**
     * Sets the Special Prices
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $specialPrices
     * @return EventDate
     */
    public function setSpecialPrices(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $specialPrices) : self
    {
        $this->specialPrices = $specialPrices;
        return $this;
    }

    /**
     * @return int
     */
    public function getSeatsNumber(): int
    {
        return $this->seatsNumber;
    }

    /**
     * @param int $seatsNumber
     */
    public function setSeatsNumber(int $seatsNumber): void
    {
        $this->seatsNumber = $seatsNumber;
    }

    /**
     * @return int
     */
    public function getSeatsTaken(): int
    {
        return $this->seatsTaken;
    }

    /**
     * @param int $seatsTaken
     */
    public function setSeatsTaken(int $seatsTaken): void
    {
        $this->seatsTaken = $seatsTaken;
    }

    /**
     * Returns the number of available seats in the event if handling the number of seats is enabled, otherwise return 0.
     *
     * @return int
     */
    public function getSeatsAvailable() : int
    {
        if (!$this->getEventDate()->isHandleSeats()) {
            return 0;
        }
        return $this->seatsNumber - $this->seatsTaken;
    }

    /**
     * Returns best Special Price
     *
     * @var array $frontendUserGroupIds
     * @return SpecialPrice|null
     */
    public function getBestSpecialPrice(array $frontendUserGroupIds = []): ?SpecialPrice
    {
        $bestSpecialPrice = null;

        if ($this->specialPrices) {
            foreach ($this->specialPrices as $specialPrice) {
                if (!isset($bestSpecialPrice) || $specialPrice->getPrice() < $bestSpecialPrice->getPrice()) {
                    if (!$specialPrice->getFrontendUserGroup() ||
                        in_array($specialPrice->getFrontendUserGroup()->getUid(), $frontendUserGroupIds)
                    ) {
                        $bestSpecialPrice = $specialPrice;
                    }
                }
            }
        }

        return $bestSpecialPrice;
    }

    /**
     * Returns price of best Special Price
     *
     * @var array $frontendUserGroupIds
     * @return float
     */
    public function getBestPrice(array $frontendUserGroupIds = []): float
    {
        $price = $this->getPrice();

        $specialPrice = $this->getBestSpecialPrice($frontendUserGroupIds);

        if ($specialPrice && $specialPrice->getPrice() < $price) {
            return $specialPrice->getPrice();
        }

        return $price;
    }

    /**
     * TODO
     *
     * @return float
     */
    public function getBestPriceCalculated(): float
    {
        return $this->price;
    }

    /**
     * TODO
     *
     * Returns best Special Price Percentage Discount
     *
     * @var array $frontendUserGroupIds
     * @return float
     */
    public function getBestSpecialPricePercentageDiscount($frontendUserGroupIds = []): float
    {
        return 0.0;
    }

    /**
     * TODO
     *
     * @return bool
     */
    public function isAvailable(): bool
    {
        if (!$this->getEventDate()->isBookable()) {
            return false;
        }
        if (!$this->getEventDate()->isHandleSeatsInPriceCategory()) {
            return $this->getEventDate()->isAvailable();
        }
        if ($this->getEventDate()->isHandleSeatsInPriceCategory() && $this->getSeatsAvailable()) {
            return true;
        }

        return false;
    }

    /**
     * TODO
     *
     * @return bool
     */
    public function isBookable(): bool
    {
        return true;
    }
}
