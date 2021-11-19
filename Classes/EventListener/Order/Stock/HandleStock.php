<?php
declare(strict_types=1);
namespace Extcode\CartEvents\EventListener\Order\Stock;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\Cart\Domain\Model\Cart\Product;
use Extcode\Cart\Event\Order\EventInterface;
use Extcode\CartEvents\Domain\Repository\EventDateRepository;
use Extcode\CartEvents\Domain\Repository\PriceCategoryRepository;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class HandleStock
{
    /**
     * @var PersistenceManager
     */
    protected $persistenceManager;

    /**
     * @var EventDateRepository
     */
    protected $eventDateRepository;

    /**
     * @var PriceCategoryRepository
     */
    protected $priceCategoryRepository;

    public function __construct(
        PersistenceManager $persistenceManager,
        EventDateRepository $eventDateRepository,
        PriceCategoryRepository $priceCategoryRepository
    ) {
        $this->persistenceManager = $persistenceManager;
        $this->eventDateRepository = $eventDateRepository;
        $this->priceCategoryRepository = $priceCategoryRepository;
    }

    public function __invoke(EventInterface $event): void
    {
        $cartProducts = $event->getCart()->getProducts();

        foreach ($cartProducts as $cartProduct) {
            if ($cartProduct->getProductType() === 'CartEvents') {
                $this->handleStockForEventDate($cartProduct);
            }
        }
    }

    /**
     * @param Product $cartProduct
     */
    protected function handleStockForEventDate(Product $cartProduct)
    {
        $eventDate = $this->eventDateRepository->findByUid($cartProduct->getProductId());

        if ($eventDate && $eventDate->isHandleSeats()) {
            if ($eventDate->isHandleSeatsInPriceCategory()) {
                foreach ($cartProduct->getBeVariants() as $cartBeVariant) {
                    $explodedId = explode('-', $cartBeVariant->getId());
                    $id = (int)end($explodedId);
                    $priceCategory = $this->priceCategoryRepository->findByUid($id);
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
