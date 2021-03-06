<?php
declare(strict_types=1);
namespace Extcode\CartEvents\Domain\Model;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

class EventDate extends AbstractEventDate
{
    /**
     * Event
     *
     * @var \Extcode\CartEvents\Domain\Model\Event
     */
    protected $event = null;

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
     * Location
     *
     * @var string
     */
    protected $location = '';

    /**
     * Lecturer
     *
     * @var string
     */
    protected $lecturer = '';

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
     * Bookable
     *
     * @var bool
     */
    protected $bookable = false;

    /**
     * Price
     *
     * @var float
     */
    protected $price = 0.0;

    /**
     * EventDate Special Price
     *
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Extcode\CartEvents\Domain\Model\SpecialPrice>
     */
    protected $specialPrices;

    /**
     * @var bool
     */
    protected $priceCategorized = false;

    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Extcode\CartEvents\Domain\Model\PriceCategory>
     */
    protected $priceCategories;

    /**
     * @var bool
     */
    protected $handleSeats = false;

    /**
     * @var bool
     */
    protected $handleSeatsInPriceCategory = false;

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
     * Calendar Entries
     *
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Extcode\CartEvents\Domain\Model\CalendarEntry>
     */
    protected $calendarEntries;

    /**
     * @return string
     */
    public function getSku() : string
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     * @return EventDate
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
     * @return EventDate
     */
    public function setTitle(string $title) : self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocation() : string
    {
        return $this->location;
    }

    /**
     * @param string $location
     * @return EventDate
     */
    public function setLocation(string $location) : self
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return string
     */
    public function getLecturer() : string
    {
        return $this->lecturer;
    }

    /**
     * @param string $lecturer
     * @return EventDate
     */
    public function setLecturer(string $lecturer) : self
    {
        $this->lecturer = $lecturer;
        return $this;
    }

    /**
     * Returns the Images
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $images
     */
    public function getImages() : \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->images;
    }

    /**
     * Returns the first Image
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $image
     */
    public function getFirstImage() : \TYPO3\CMS\Extbase\Domain\Model\FileReference
    {
        return array_shift($this->getImages()->toArray());
    }

    /**
     * Sets the Images
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $images
     * @return EventDate
     */
    public function setImages($images) : self
    {
        $this->images = $images;
        return $this;
    }

    /**
     * Returns the Files
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $files
     */
    public function getFiles() : \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->files;
    }

    /**
     * Sets the Files
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $files
     * @return EventDate
     */
    public function setFiles($files) : self
    {
        $this->files = $files;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBookable() : bool
    {
        return $this->bookable;
    }

    /**
     * @param bool $bookable
     * @return EventDate
     */
    public function setBookable(bool $bookable) : self
    {
        $this->bookable = $bookable;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice() : float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return EventDate
     */
    public function setPrice(float $price) : self
    {
        $this->price = $price;
        return $this;
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
     * @return bool
     */
    public function isPriceCategorized(): bool
    {
        return $this->priceCategorized;
    }

    /**
     * @param bool $priceCategorized
     */
    public function setPriceCategorized(bool $priceCategorized): void
    {
        $this->priceCategorized = $priceCategorized;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getPriceCategories(): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->priceCategories;
    }

    /**
     * @return PriceCategory|null
     */
    public function getFirstAvailablePriceCategory(): ?\Extcode\CartEvents\Domain\Model\PriceCategory
    {
        foreach ($this->getPriceCategories() as $priceCategory) {
            if ($priceCategory->isAvailable()) {
                return $priceCategory;
            }
        }

        return null;
    }

    /**
     * Adds a Special Price
     *
     * @param \Extcode\CartEvents\Domain\Model\PriceCategory $priceCategory
     * @return EventDate
     */
    public function addPriceCategory(\Extcode\CartEvents\Domain\Model\PriceCategory $priceCategory) : self
    {
        $this->priceCategories->attach($priceCategory);
        return $this;
    }

    /**
     * Removes a Special Price
     *
     * @param \Extcode\CartEvents\Domain\Model\PriceCategory $priceCategory
     * @return EventDate
     */
    public function removePriceCategory(\Extcode\CartEvents\Domain\Model\PriceCategory $priceCategory) : self
    {
        $this->priceCategories->detach($priceCategory);
        return $this;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $priceCategories
     */
    public function setPriceCategories(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $priceCategories): void
    {
        $this->priceCategories = $priceCategories;
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
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getCalendarEntries() : \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        $sortedCalendarEntries = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $calendarEntryArr = $this->calendarEntries->toArray();

        usort($calendarEntryArr, function ($calendarEntry1, $calendarEntry2) {
            $begin1 = $calendarEntry1->getBegin();
            $begin2 = $calendarEntry2->getBegin();

            if ($begin1 === $begin2) {
                return 0;
            }

            return $begin1 < $begin2 ? -1 : 1;
        });

        foreach ($calendarEntryArr as $calendarEntry) {
            $sortedCalendarEntries->attach($calendarEntry);
        }

        return $sortedCalendarEntries;
    }

    /**
     * return \Extcode\CartEvents\Domain\Model\CalendarEntry
     */
    public function getFirstCalendarEntry() : \Extcode\CartEvents\Domain\Model\CalendarEntry
    {
        return $this->getCalendarEntries()->current();
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $calendarEntries
     * @return EventDate
     */
    public function setCalendarEntries(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $calendarEntries) : self
    {
        $this->calendarEntries = $calendarEntries;
        return $this;
    }

    /**
     * @return Event
     */
    public function getEvent() : Event
    {
        return $this->event;
    }

    /**
     * @param Event $event
     * @return EventDate
     */
    public function setEvent(Event $event) : self
    {
        $this->event = $event;
        return $this;
    }

    /**
     * @return bool
     */
    public function isHandleSeats() : bool
    {
        return $this->handleSeats;
    }

    /**
     * @param bool $handleSeats
     * @return EventDate
     */
    public function setHandleSeats(bool $handleSeats) : self
    {
        $this->handleSeats = $handleSeats;
        return $this;
    }

    /**
     * @return bool
     */
    public function isHandleSeatsInPriceCategory(): bool
    {
        return $this->handleSeatsInPriceCategory;
    }

    /**
     * @param bool $handleSeatsInPriceCategory
     */
    public function setHandleSeatsInPriceCategory(bool $handleSeatsInPriceCategory): void
    {
        $this->handleSeatsInPriceCategory = $handleSeatsInPriceCategory;
    }

    /**
     * Returns the number of seats in the event if handling the number of seats is enabled, otherwise return 0.
     *
     * @return int
     */
    public function getSeatsNumber() : int
    {
        if (!$this->isHandleSeats()) {
            return 0;
        }
        if ($this->isHandleSeatsInPriceCategory()) {
            $seats = 0;

            foreach ($this->getPriceCategories() as $priceCategory) {
                $seats += $priceCategory->getSeatsNumber();
            }

            return $seats;
        }
        return $this->seatsNumber;
    }

    /**
     * @param int $seatsNumber
     * @return EventDate
     */
    public function setSeatsNumber(int $seatsNumber) : self
    {
        $this->seatsNumber = $seatsNumber;
        return $this;
    }

    /**
     * Returns the number of taken seats in the event if handling the number of seats is enabled, otherwise return 0.
     *
     * @return int
     */
    public function getSeatsTaken() : int
    {
        if (!$this->isHandleSeats()) {
            return 0;
        }
        if ($this->isHandleSeatsInPriceCategory()) {
            $seats = 0;

            foreach ($this->getPriceCategories() as $priceCategory) {
                $seats += $priceCategory->getSeatsTaken();
            }

            return $seats;
        }
        return $this->seatsTaken;
    }

    /**
     * @param int $seatsTaken
     * @return EventDate
     */
    public function setSeatsTaken(int $seatsTaken) : self
    {
        $this->seatsTaken = $seatsTaken;
        return $this;
    }

    /**
     * Returns the number of available seats in the event if handling the number of seats is enabled, otherwise return 0.
     *
     * @return int
     */
    public function getSeatsAvailable() : int
    {
        if (!$this->isHandleSeats()) {
            return 0;
        }
        if ($this->isHandleSeatsInPriceCategory()) {
            $seats = 0;

            foreach ($this->getPriceCategories() as $priceCategory) {
                $seats += $priceCategory->getSeatsAvailable();
            }

            return $seats;
        }
        return $this->seatsNumber - $this->seatsTaken;
    }

    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        if (!$this->isBookable()) {
            return false;
        }
        if (!$this->isHandleSeats()) {
            return true;
        }
        if ($this->getSeatsAvailable()) {
            return true;
        }

        return false;
    }
}
