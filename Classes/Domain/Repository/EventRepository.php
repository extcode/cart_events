<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Domain\Repository;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\CartEvents\Domain\Model\Dto\EventDemand;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class EventRepository extends Repository
{
    public function findDemanded(EventDemand $demand): QueryResultInterface
    {
        $query = $this->createQuery();

        $constraints = [];

        if ($demand->getSku()) {
            $constraints[] = $query->equals('sku', $demand->getSku());
        }
        if ($demand->getTitle()) {
            $constraints[] = $query->like('title', '%' . $demand->getTitle() . '%');
        }

        if (!empty($demand->getCategories())) {
            $categoryConstraints = [];
            foreach ($demand->getCategories() as $category) {
                $categoryConstraints[] = $query->equals('category', $category);
                $categoryConstraints[] = $query->contains('categories', $category);
            }
            $constraints[] = $query->logicalOr(...array_values($categoryConstraints));
        }

        if (!empty($constraints)) {
            $query->matching(
                $query->logicalAnd(...array_values($constraints))
            );
        }

        if ($orderings = $this->createOrderingsFromDemand($demand)) {
            $query->setOrderings($orderings);
        }

        if ($limit = $demand->getLimit()) {
            $query->setLimit($limit);
        }

        return $query->execute();
    }

    /**
     * Find all events based on selected uids
     */
    public function findByUids(string $uids, ?int $limit = null): array
    {
        $uids = explode(',', $uids);

        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $query->matching(
            $query->in('uid', $uids)
        );
        if ($limit) {
            $query->setLimit($limit);
        }

        return $this->orderByField($query->execute(), $uids);
    }

    protected function createOrderingsFromDemand(EventDemand $demand): array
    {
        $orderings = [];

        $orderList = GeneralUtility::trimExplode(',', $demand->getOrder(), true);

        if (!empty($orderList)) {
            foreach ($orderList as $orderItem) {
                [$orderField, $ascDesc] =
                    GeneralUtility::trimExplode(' ', $orderItem, true);
                if ($ascDesc) {
                    $orderings[$orderField] = ((strtolower($ascDesc) === 'desc') ?
                        QueryInterface::ORDER_DESCENDING :
                        QueryInterface::ORDER_ASCENDING);
                } else {
                    $orderings[$orderField] = QueryInterface::ORDER_ASCENDING;
                }
            }
        }

        return $orderings;
    }

    protected function orderByField(QueryResultInterface $events, array $uids): array
    {
        $indexedEvents = [];
        $orderedEvents = [];

        // Create an associative array
        foreach ($events as $object) {
            $indexedEvents[$object->getUid()] = $object;
        }
        // add to ordered array in right order
        foreach ($uids as $uid) {
            if (isset($indexedEvents[$uid])) {
                $orderedEvents[] = $indexedEvents[$uid];
            }
        }

        return $orderedEvents;
    }
}
