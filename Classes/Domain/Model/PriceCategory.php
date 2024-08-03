<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Domain\Model;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */
use TYPO3\CMS\Extbase\Annotation\ORM\Cascade;
use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class PriceCategory extends AbstractEntity
{
    protected EventDate $eventDate;

    #[Validate(['validator' => 'NotEmpty'])]
    protected string $sku;

    #[Validate(['validator' => 'NotEmpty'])]
    protected string $title = '';

    protected float $price = 0.0;

    /**
     * @var ObjectStorage<SpecialPrice>
     */
    #[Cascade(['value' => 'remove'])]
    protected ObjectStorage $specialPrices;

    protected int $seatsNumber = 0;

    protected int $seatsTaken = 0;

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    public function getEventDate(): EventDate
    {
        return $this->eventDate;
    }

    public function setEventDate(EventDate $eventDate): void
    {
        $this->eventDate = $eventDate;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return ObjectStorage<SpecialPrice>
     */
    public function getSpecialPrices(): ?ObjectStorage
    {
        return $this->specialPrices;
    }

    public function addSpecialPrice(SpecialPrice $specialPrice): void
    {
        $this->specialPrices->attach($specialPrice);
    }

    public function removeSpecialPrice(SpecialPrice $specialPrice): void
    {
        $this->specialPrices->detach($specialPrice);
    }

    public function setSpecialPrices(ObjectStorage $specialPrices): void
    {
        $this->specialPrices = $specialPrices;
    }

    public function getSeatsNumber(): int
    {
        return $this->seatsNumber;
    }

    public function setSeatsNumber(int $seatsNumber): void
    {
        $this->seatsNumber = $seatsNumber;
    }

    public function getSeatsTaken(): int
    {
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
        if (!$this->getEventDate()->isHandleSeats()) {
            return 0;
        }
        return $this->seatsNumber - $this->seatsTaken;
    }

    /**
     * Returns best Special Price
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
     */
    public function getBestPriceCalculated(): float
    {
        return $this->price;
    }

    /**
     * TODO
     *
     * Returns best Special Price Percentage Discount
     */
    public function getBestSpecialPricePercentageDiscount($frontendUserGroupIds = []): float
    {
        return 0.0;
    }

    /**
     * TODO
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
     */
    public function isBookable(): bool
    {
        return true;
    }
}
