<?php
declare(strict_types=1);
namespace Extcode\CartEvents\Domain\Model;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class EventDate extends AbstractEventDate
{
    /**
     * @var Event
     */
    protected $event;

    /**
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $sku;

    /**
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $location = '';

    /**
     * @var string
     */
    protected $lecturer = '';

    /**
     * @var ObjectStorage<FileReference>
     */
    protected $images;

    /**
     * @var ObjectStorage<FileReference>
     */
    protected $files;

    /**
     * @var bool
     */
    protected $bookable = false;

    /**
     * @var float
     */
    protected $price = 0.0;

    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     * @var ObjectStorage<SpecialPrice>
     */
    protected $specialPrices;

    /**
     * @var bool
     */
    protected $priceCategorized = false;

    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     * @var ObjectStorage<PriceCategory>
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
     * @var int
     */
    protected $seatsNumber = 0;

    /**
     * @var int
     */
    protected $seatsTaken = 0;

    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     * @var ObjectStorage<CalendarEntry>
     */
    protected $calendarEntries;

    /**
     * @return string
     */
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

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;
        return $this;
    }

    public function getLecturer(): string
    {
        return $this->lecturer;
    }

    public function setLecturer(string $lecturer): self
    {
        $this->lecturer = $lecturer;
        return $this;
    }

    /**
     * @return ObjectStorage<FileReference>
     */
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

    /**
     * @return ObjectStorage<FileReference>
     */
    public function getFiles(): ?ObjectStorage
    {
        return $this->files;
    }

    public function setFiles(ObjectStorage $files): self
    {
        $this->files = $files;
        return $this;
    }

    public function isBookable(): bool
    {
        return $this->bookable;
    }

    public function setBookable(bool $bookable): self
    {
        $this->bookable = $bookable;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return ObjectStorage<SpecialPrice>
     */
    public function getSpecialPrices(): ?ObjectStorage
    {
        return $this->specialPrices;
    }

    public function addSpecialPrice(SpecialPrice $specialPrice): self
    {
        $this->specialPrices->attach($specialPrice);
        return $this;
    }

    public function removeSpecialPrice(SpecialPrice $specialPrice): self
    {
        $this->specialPrices->detach($specialPrice);
        return $this;
    }

    public function setSpecialPrices(ObjectStorage $specialPrices): self
    {
        $this->specialPrices = $specialPrices;
        return $this;
    }

    public function isPriceCategorized(): bool
    {
        return $this->priceCategorized;
    }

    public function setPriceCategorized(bool $priceCategorized): void
    {
        $this->priceCategorized = $priceCategorized;
    }

    public function getPriceCategories(): ?ObjectStorage
    {
        return $this->priceCategories;
    }

    public function getFirstAvailablePriceCategory(): ?PriceCategory
    {
        foreach ($this->getPriceCategories() as $priceCategory) {
            if ($priceCategory->isAvailable()) {
                return $priceCategory;
            }
        }

        return null;
    }

    public function addPriceCategory(PriceCategory $priceCategory): self
    {
        $this->priceCategories->attach($priceCategory);
        return $this;
    }

    public function removePriceCategory(PriceCategory $priceCategory): self
    {
        $this->priceCategories->detach($priceCategory);
        return $this;
    }

    public function setPriceCategories(ObjectStorage $priceCategories): void
    {
        $this->priceCategories = $priceCategories;
    }

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

    public function getBestPrice(array $frontendUserGroupIds = []): float
    {
        $price = $this->getPrice();

        $specialPrice = $this->getBestSpecialPrice($frontendUserGroupIds);

        if ($specialPrice && $specialPrice->getPrice() < $price) {
            return $specialPrice->getPrice();
        }

        return $price;
    }

    public function getCalendarEntries(): ?ObjectStorage
    {
        $sortedCalendarEntries = new ObjectStorage();
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

    public function getFirstCalendarEntry(): ?CalendarEntry
    {
        if (!$this->getCalendarEntries()) {
            return null;
        }
        return $this->getCalendarEntries()->current();
    }

    public function setCalendarEntries(ObjectStorage $calendarEntries): self
    {
        $this->calendarEntries = $calendarEntries;
        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): self
    {
        $this->event = $event;
        return $this;
    }

    public function isHandleSeats(): bool
    {
        return $this->handleSeats;
    }

    public function setHandleSeats(bool $handleSeats): self
    {
        $this->handleSeats = $handleSeats;
        return $this;
    }

    public function isHandleSeatsInPriceCategory(): bool
    {
        return $this->handleSeatsInPriceCategory;
    }

    public function setHandleSeatsInPriceCategory(bool $handleSeatsInPriceCategory): void
    {
        $this->handleSeatsInPriceCategory = $handleSeatsInPriceCategory;
    }

    /**
     * Returns the number of seats in the event if handling the number of seats
     * is enabled, otherwise return 0.
     */
    public function getSeatsNumber(): int
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

    public function setSeatsNumber(int $seatsNumber): self
    {
        $this->seatsNumber = $seatsNumber;
        return $this;
    }

    /**
     * Returns the number of taken seats in the event if handling the number of
     * seats is enabled, otherwise return 0.
     *
     * @return int
     */
    public function getSeatsTaken(): int
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

    public function setSeatsTaken(int $seatsTaken): self
    {
        $this->seatsTaken = $seatsTaken;
        return $this;
    }

    /**
     * Returns the number of available seats in the event if handling the
     * number of seats is enabled, otherwise return 0.
     */
    public function getSeatsAvailable(): int
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
