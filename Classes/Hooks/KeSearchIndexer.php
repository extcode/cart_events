<?php

namespace Extcode\CartEvents\Hooks;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Tpwd\KeSearch\Indexer\IndexerRunner;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\QueryGenerator;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class KeSearchIndexer
{
    protected $indexerKey = '';
    protected $indexerName = '';

    public function registerIndexerConfiguration(array &$params, $pObj): void
    {
        if (!empty($this->indexerKey) && !empty($this->indexerName)) {
            $newArray = [
                $this->indexerName,
                $this->indexerKey,
                ExtensionManagementUtility::extPath('cart_events') . 'ext_icon.svg'
            ];
            $params['items'][] = $newArray;
        }
    }

    public function customIndexer(array &$indexerConfig, IndexerRunner &$indexerObject): string
    {
        if ($indexerConfig['type'] === $this->indexerKey) {
            return $this->cartEventIndexer($indexerConfig, $indexerObject);
        }

        return '';
    }

    abstract public function cartEventIndexer(array &$indexerConfig, IndexerRunner &$indexerObject): string;

    /**
     * Returns all Storage Pids for indexing
     */
    protected function getPidList(array $config): string
    {
        $recursivePids = $this->extendPidListByChildren($config['startingpoints_recursive'], 99);
        if ($config['sysfolder']) {
            return $recursivePids . ',' . $config['sysfolder'];
        }

        return $recursivePids;
    }

    /**
     * Find all ids from given ids and level
     */
    protected function extendPidListByChildren(string $pidList = '', $recursive = 0): string
    {
        $recursive = (int)$recursive;

        if ($recursive <= 0) {
            return $pidList;
        }

        $queryGenerator = GeneralUtility::makeInstance(
            QueryGenerator::class
        );
        $recursiveStoragePids = $pidList;
        $storagePids = GeneralUtility::intExplode(',', $pidList);
        foreach ($storagePids as $startPid) {
            $pids = $queryGenerator->getTreeList($startPid, $recursive, 0, 1);
            if (strlen($pids) > 0) {
                $recursiveStoragePids .= ',' . $pids;
            }
        }
        return $recursiveStoragePids;
    }

    /**
     * Returns all events for a given PidList
     */
    protected function getEventsToIndex(string $indexPids): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_cartevents_domain_model_event');

        $queryBuilder
            ->select('*')
            ->from('tx_cartevents_domain_model_event')
            ->where(
                $queryBuilder->expr()->in('tx_cartevents_domain_model_event.pid', $indexPids)
            );

        return $queryBuilder->execute()->fetchAll();
    }

    protected function getTargetPidFormPage(int $eventUid): ?int
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('pages');

        $constraints = [
            $queryBuilder->expr()->eq('pages.doktype', $queryBuilder->createNamedParameter('186', \PDO::PARAM_INT)),
            $queryBuilder->expr()->eq('pages.cart_events_event', $queryBuilder->createNamedParameter($eventUid, \PDO::PARAM_INT)),
        ];

        $queryBuilder
            ->select('pages.uid')
            ->from('pages')
            ->where(...$constraints);

        $page = $queryBuilder->execute()->fetch();

        return $page['uid'];
    }

    /**
     *
     */
    protected function getTargetPidFormCategory(int $categoryUid): ?int
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_category');

        $constraints = [
            $queryBuilder->expr()->eq('sys_category_mm.tablenames', $queryBuilder->createNamedParameter('tx_cartevents_domain_model_event', \PDO::PARAM_STR)),
            $queryBuilder->expr()->eq('sys_category_mm.fieldname', $queryBuilder->createNamedParameter('category', \PDO::PARAM_STR)),
            $queryBuilder->expr()->eq('sys_category_mm.uid_foreign', $queryBuilder->createNamedParameter($categoryUid, \PDO::PARAM_INT)),
        ];

        $queryBuilder
            ->select('sys_category.cart_event_show_pid')
            ->from('sys_category')
            ->leftJoin(
                'sys_category',
                'sys_category_record_mm',
                'sys_category_mm',
                $queryBuilder->expr()->eq(
                    'sys_category_mm.uid_local',
                    $queryBuilder->quoteIdentifier('sys_category.uid')
                )
            )
            ->where(...$constraints);

        $sysCategory = $queryBuilder->execute()->fetch();

        return $sysCategory['cart_event_show_pid'];
    }
}
