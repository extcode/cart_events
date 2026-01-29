<?php

declare(strict_types=1);

namespace Extcode\CartEvents\EventListener\Order\Stock;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Exception;
use Extcode\Cart\Domain\Model\Cart\Product;
use Extcode\Cart\Event\Order\EventInterface;
use Extcode\CartEvents\Domain\Model\EventDate;
use Extcode\CartEvents\Domain\Model\PriceCategory;
use Extcode\CartEvents\Domain\Repository\EventDateRepository;
use Extcode\CartEvents\Domain\Repository\PriceCategoryRepository;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class HandleStock
{
    public function __construct(
        private readonly PersistenceManager $persistenceManager,
        private readonly EventDateRepository $eventDateRepository,
        private readonly PriceCategoryRepository $priceCategoryRepository,
    ) {}

    public function __invoke(EventInterface $event): void
    {
        $cartProducts = $event->getCart()->getProducts();

        foreach ($cartProducts as $cartProduct) {
            if ($cartProduct->getProductType() === 'CartEvents') {
                $this->handleStockForEventDate($cartProduct);
            }
        }
    }

    protected function handleStockForEventDate(Product $cartProduct): void
    {
        $eventDate = $this->eventDateRepository->findByUid($cartProduct->getProductId());

        if (($eventDate instanceof EventDate) === false) {
            return;
        }

        if ($eventDate->isHandleSeats()) {
            if ($eventDate->isHandleSeatsInPriceCategory()) {
                foreach ($cartProduct->getBeVariants() as $cartBeVariant) {
                    $explodedId = explode('-', (string)$cartBeVariant->getId());
                    $id = (int)end($explodedId);
                    $priceCategory = $this->priceCategoryRepository->findByUid($id);
                    if (($priceCategory instanceof PriceCategory) === false) {
                        throw new Exception('Can not find PriceCategory with $id=' . $id, 1769617418);
                    }
                    $priceCategory->setSeatsTaken($priceCategory->getSeatsTaken() + $cartBeVariant->getQuantity());
                    $this->priceCategoryRepository->update($priceCategory);
                }
            } else {
                $eventDate->setSeatsTaken($eventDate->getSeatsTaken() + $cartProduct->getQuantity());
                $this->eventDateRepository->update($eventDate);
            }

            $this->persistenceManager->persistAll();
        }
    }
}
