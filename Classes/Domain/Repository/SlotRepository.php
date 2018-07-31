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
     * @param string $pidList
     *
     * @return \Doctrine\DBAL\Driver\Statement|int
     */
    public function findNext(int $limit, string $pidList)
    {
        $table = 'tx_cartevents_domain_model_slot';
        $joinTableDate = 'tx_cartevents_domain_model_date';
        $joinTableEvent = 'tx_cartevents_domain_model_event';

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($table);

        $queryBuilder
            ->select('tx_cartevents_domain_model_slot.uid')
            ->addSelect('date.begin')
            ->addSelect('event.uid AS event_uid')
            ->addSelect('event.pid AS event_pid')
            //->addSelect('category.cart_event_list_pid')
            //->addSelect('category.cart_event_show_pid')
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

        //$this->joinCategory($queryBuilder);
        $queryBuilder->andWhere(
            'date.begin >= ' . time()
        );
        $queryBuilder->andWhere(
            $queryBuilder->expr()->in(
                'event.pid',
                $this->getPids($pidList)
            )
        );

        $queryBuilder
            ->orderBy('date.begin')
            ->groupBy('slot');

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

    /**
     * @param string $pidList
     * @return array
     */
    protected function getPids(string $pidList):array
    {
        return GeneralUtility::intExplode(',', $pidList, true);
    }
}
