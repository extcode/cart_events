<?php
declare(strict_types=1);

namespace Extcode\CartEvents\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Slot extends AbstractEntity
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
     * @validate NotEmpty
     */
    protected $sku;

    /**
     * Title
     *
     * @var string
     * @validate NotEmpty
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
     * Event Special Price
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Extcode\CartEvents\Domain\Model\SpecialPrice>
     * @cascade remove
     */
    protected $specialPrices;

    /**
     * Handle number of seats
     *
     * @var bool
     */
    protected $handleSeats;

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
     * Dates
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Extcode\CartEvents\Domain\Model\Date>
     * @cascade remove
     */
    protected $dates;

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
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation(string $location)
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getLecturer(): string
    {
        return $this->lecturer;
    }

    /**
     * @param string $lecturer
     */
    public function setLecturer(string $lecturer)
    {
        $this->lecturer = $lecturer;
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
     * @return bool
     */
    public function isBookable(): bool
    {
        return $this->bookable;
    }

    /**
     * @param bool $bookable
     */
    public function setBookable(bool $bookable)
    {
        $this->bookable = $bookable;
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
    public function setPrice(float $price)
    {
        $this->price = $price;
    }

    /**
     * Returns the Special Prices
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Extcode\CartEvents\Domain\Model\SpecialPrice>
     */
    public function getSpecialPrices()
    {
        return $this->specialPrices;
    }

    /**
     * Adds a Special Price
     *
     * @param \Extcode\CartEvents\Domain\Model\SpecialPrice $specialPrice
     */
    public function addSpecialPrice(\Extcode\CartEvents\Domain\Model\SpecialPrice $specialPrice)
    {
        $this->specialPrices->attach($specialPrice);
    }

    /**
     * Removes a Special Price
     *
     * @param \Extcode\CartEvents\Domain\Model\SpecialPrice $specialPriceToRemove
     */
    public function removeSpecialPrice(\Extcode\CartEvents\Domain\Model\SpecialPrice $specialPriceToRemove)
    {
        $this->specialPrices->detach($specialPriceToRemove);
    }

    /**
     * Sets the Special Prices
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $specialPrices
     */
    public function setSpecialPrices(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $specialPrices)
    {
        $this->specialPrices = $specialPrices;
    }

    /**
     * Returns best Special Price
     *
     * @var array $frontendUserGroupIds
     * @return float
     */
    public function getBestSpecialPrice($frontendUserGroupIds = [])
    {
        $bestSpecialPrice = $this->price;

        if ($this->specialPrices) {
            foreach ($this->specialPrices as $specialPrice) {
                if ($specialPrice->getPrice() < $bestSpecialPrice) {
                    if (!$specialPrice->getFrontendUserGroup() ||
                        in_array($specialPrice->getFrontendUserGroup()->getUid(), $frontendUserGroupIds)
                    ) {
                        $bestSpecialPrice = $specialPrice->getPrice();
                    }
                }
            }
        }

        return $bestSpecialPrice;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getDates(): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        $sortedDates = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $dates = $this->dates->toArray();

        usort($dates, function ($date1, $date2) {
            $begin1 = $date1->getBegin();
            $begin2 = $date2->getBegin();

            if ($begin1 == $begin2) {
                return 0;
            }

            return $begin1 < $begin2 ? -1 : 1;
        });

        foreach ($dates as $date) {
            $sortedDates->attach($date);
        }

        return $sortedDates;
    }

    /**
     * return \Extcode\CartEvents\Domain\Model\Date
     */
    public function getFirstDate()
    {
        return $this->getDates()->current();
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $dates
     */
    public function setDates(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $dates)
    {
        $this->dates = $dates;
    }

    /**
     * @return Event
     */
    public function getEvent(): Event
    {
        return $this->event;
    }

    /**
     * @param Event $event
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;
    }

    /**
     * @return bool
     */
    public function isHandleSeats(): bool
    {
        return $this->handleSeats;
    }

    /**
     * @param bool $handleSeats
     */
    public function setHandleSeats(bool $handleSeats)
    {
        $this->handleSeats = $handleSeats;
    }

    /**
     * Returns the number of seats in the event if handling the number of seats is enabled, otherwise return 0.
     *
     * @return int
     */
    public function getSeatsNumber(): int
    {
        if (!$this->handleSeats) {
            return 0;
        }
        return $this->seatsNumber;
    }

    /**
     * @param int $seatsNumber
     */
    public function setSeatsNumber(int $seatsNumber)
    {
        $this->seatsNumber = $seatsNumber;
    }

    /**
     * Returns the number of taken seats in the event if handling the number of seats is enabled, otherwise return 0.
     *
     * @return int
     */
    public function getSeatsTaken(): int
    {
        if (!$this->handleSeats) {
            return 0;
        }
        return $this->seatsTaken;
    }

    /**
     * @param int $seatsTaken
     */
    public function setSeatsTaken(int $seatsTaken)
    {
        $this->seatsTaken = $seatsTaken;
    }

    /**
     * Returns the number of available seats in the event if handling the number of seats is enabled, otherwise return 0.
     *
     * @return int
     */
    public function getSeatsAvailable(): int
    {
        if (!$this->handleSeats) {
            return 0;
        }
        return $this->seatsNumber - $this->seatsTaken;
    }
}
