<?php
declare(strict_types=1);

namespace Extcode\CartEvents\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;

class SlotRepository extends Repository
{
    /**
     * @param int $limit
     *
     * @return \Doctrine\DBAL\Driver\Statement|int
     */
    public function findNext(int $limit)
    {
        $table = 'tx_cartevents_domain_model_slot';
        $joinTableDate = 'tx_cartevents_domain_model_date';
        $joinTableEvent = 'tx_cartevents_domain_model_event';

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($table);

        $queryBuilder
            ->select('tx_cartevents_domain_model_slot.*')
            ->addSelect('date.*')
            ->addSelect('event.*')
            ->addSelect('category.cart_event_list_pid')
            ->addSelect('category.cart_event_show_pid')
            ->from($table)
            ->leftJoin(
                $table,
                $joinTableDate,
                'date',
                $queryBuilder->expr()->eq(
                    'date.slot',
                    $queryBuilder->quoteIdentifier('tx_cartevents_domain_model_slot.uid')
                )
            )
            ->leftJoin(
                $table,
                $joinTableEvent,
                'event',
                $queryBuilder->expr()->eq(
                    'event.uid',
                    $queryBuilder->quoteIdentifier('tx_cartevents_domain_model_slot.event')
                )
            );

        $this->joinCategory($queryBuilder);
        $queryBuilder->andWhere('begin >= ' . time());

        $queryBuilder
            ->orderBy('begin')
            ->groupBy('event');

        if ($limit) {
            $queryBuilder->setMaxResults($limit);
        }

        $data = $queryBuilder->execute();

        return $data;
    }

    /**
     * @param $queryBuilder
     * @return mixed
     */
    protected function joinCategory($queryBuilder)
    {
        return $queryBuilder
            ->leftJoin(
                'event',
                'sys_category_record_mm',
                'categoryMM',
                $queryBuilder->expr()->eq(
                    'categoryMM.uid_foreign',
                    $queryBuilder->quoteIdentifier('event.uid')
                )
            )
            ->where(
                $queryBuilder->expr()->eq('categoryMM.tablenames', '"tx_cartevents_domain_model_event"'),
                $queryBuilder->expr()->eq('categoryMM.fieldname', '"category"')
            )
            ->leftJoin(
                'categoryMM',
                'sys_category',
                'category',
                $queryBuilder->expr()->eq(
                    'category.uid',
                    $queryBuilder->quoteIdentifier('categoryMM.uid_local')
                )
            );
    }
}
