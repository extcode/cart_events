<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Domain\Model;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation\ORM\Cascade;
use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class EventDate extends AbstractEventDate
{
    protected Event $event;

    #[Validate(['validator' => 'NotEmpty'])]
    protected string $sku = '';

    #[Validate(['validator' => 'NotEmpty'])]
    protected string $title = '';

    protected string $location = '';

    protected string $lecturer = '';

    /**
     * @var ObjectStorage<FileReference>
     */
    protected ObjectStorage $images;

    /**
     * @var ObjectStorage<FileReference>
     */
    protected ObjectStorage $files;

    protected bool $bookable = false;

    protected float $price = 0.0;

    /**
     * @var ObjectStorage<SpecialPrice>
     */
    #[Cascade(['value' => 'remove'])]
    protected ObjectStorage $specialPrices;

    protected bool $priceCategorized = false;

    /**
     * @var ObjectStorage<PriceCategory>
     */
    #[Cascade(['value' => 'remove'])]
    protected ObjectStorage $priceCategories;

    protected bool $handleSeats = false;

    protected bool $handleSeatsInPriceCategory = false;

    protected int $seatsNumber = 0;

    protected int $seatsTaken = 0;

    /**
     * @var ObjectStorage<CalendarEntry>
     */
    #[Cascade(['value' => 'remove'])]
    protected ObjectStorage $calendarEntries;

    public function __construct()
    {
        $this->images = new ObjectStorage();
        $this->files = new ObjectStorage();
        $this->specialPrices = new ObjectStorage();
        $this->priceCategories = new ObjectStorage();
        $this->calendarEntries = new ObjectStorage();
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getLecturer(): string
    {
        return $this->lecturer;
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

    /**
     * @return ObjectStorage<FileReference>
     */
    public function getFiles(): ?ObjectStorage
    {
        return $this->files;
    }

    public function isBookable(): bool
    {
        return $this->bookable;
    }

    public function setBookable(bool $bookable): void
    {
        $this->bookable = $bookable;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return ObjectStorage<SpecialPrice>
     */
    public function getSpecialPrices(): ?ObjectStorage
    {
        return $this->specialPrices;
    }

    public function isPriceCategorized(): bool
    {
        return $this->priceCategorized;
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

    public function getBestSpecialPrice(?array $frontendUserGroupIds = null): ?SpecialPrice
    {
        if (is_null($frontendUserGroupIds)) {
            $context = GeneralUtility::makeInstance(Context::class);
            $frontendUserGroupIds = $context->getPropertyFromAspect('frontend.user', 'groupIds');
        }

        $bestSpecialPrice = null;

        if ($this->specialPrices) {
            foreach ($this->specialPrices as $specialPrice) {
                if (!isset($bestSpecialPrice) || $specialPrice->getPrice() < $bestSpecialPrice->getPrice()) {
                    if (!$specialPrice->getFrontendUserGroup()
                        || in_array($specialPrice->getFrontendUserGroup()->getUid(), $frontendUserGroupIds)
                    ) {
                        $bestSpecialPrice = $specialPrice;
                    }
                }
            }
        }

        return $bestSpecialPrice;
    }

    public function getBestPrice(?array $frontendUserGroupIds = null): float
    {
        if (is_null($frontendUserGroupIds)) {
            $context = GeneralUtility::makeInstance(Context::class);
            $frontendUserGroupIds = $context->getPropertyFromAspect('frontend.user', 'groupIds');
        }

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
            return $begin1 <=> $begin2;
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

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function isHandleSeats(): bool
    {
        return $this->handleSeats;
    }

    public function isHandleSeatsInPriceCategory(): bool
    {
        return $this->handleSeatsInPriceCategory;
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

    public function setSeatsTaken(int $seatsTaken): void
    {
        $this->seatsTaken = $seatsTaken;
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

        return $this->getSeatsAvailable() > 0;
    }
}
