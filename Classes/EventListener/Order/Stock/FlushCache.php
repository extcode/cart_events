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
use Extcode\Cart\Event\Order\EventInterface;
use Extcode\CartEvents\Domain\Model\Event;
use Extcode\CartEvents\Domain\Model\EventDate;
use Extcode\CartEvents\Domain\Repository\EventDateRepository;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

readonly class FlushCache
{
    public function __construct(
        private EventDateRepository $eventDateRepository
    ) {}

    public function __invoke(EventInterface $event): void
    {
        $cartProducts = $event->getCart()->getProducts();

        foreach ($cartProducts as $cartProduct) {
            if ($cartProduct->getProductType() === 'CartEvents') {
                $eventDate = $this->eventDateRepository->findByUid($cartProduct->getProductId());
                if (($eventDate instanceof EventDate) === false) {
                    throw new Exception('Can not find EventDate with uid ' . $cartProduct->getProductId() . ' has no event!', 1769617880);
                }
                $event = $eventDate->getEvent();
                if (($event instanceof Event) === false) {
                    throw new Exception('EventDate with uid ' . $cartProduct->getProductId() . ' has no event!', 1769617883);
                }
                $cacheTag = 'tx_cartevents_event_' . $event->getUid();
                $cacheManager = GeneralUtility::makeInstance(CacheManager::class);
                $cacheManager->flushCachesInGroupByTag('pages', $cacheTag);
            }
        }
    }
}
