<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Domain\Repository;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;

class EventDateRepository extends Repository
{
    public function findNext(int $limit, string $pidList): array
    {
        $table = 'tx_cartevents_domain_model_eventdate';
        $joinTableEvent = 'tx_cartevents_domain_model_event';

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($table);

        $queryBuilder
            ->select('tx_cartevents_domain_model_eventdate.uid', 'tx_cartevents_domain_model_eventdate.begin')
            ->addSelect('event.uid AS event_uid')
            ->addSelect('event.pid AS event_pid')
            ->from($table)
            ->leftJoin(
                $table,
                $joinTableEvent,
                'event',
                $queryBuilder->expr()->eq(
                    'event.uid',
                    $queryBuilder->quoteIdentifier('tx_cartevents_domain_model_eventdate.event')
                )
            );

        $queryBuilder->andWhere(
            'tx_cartevents_domain_model_eventdate.begin >= ' . time()
        );
        $queryBuilder->andWhere(
            $queryBuilder->expr()->in(
                'event.pid',
                $this->getPids($pidList)
            )
        );

        $queryBuilder
            ->orderBy('tx_cartevents_domain_model_eventdate.begin')
            ->groupBy('tx_cartevents_domain_model_eventdate.uid');

        if ($limit) {
            $queryBuilder->setMaxResults($limit);
        }

        return $queryBuilder->executeQuery()->fetchAllAssociative();
    }

    private function getPids(string $pidList): array
    {
        return GeneralUtility::intExplode(',', $pidList, true);
    }
}
