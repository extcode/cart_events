<?php

namespace Extcode\CartEvents\Hooks;

/**
 * This file is part of the "cart_events" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class KeSearchIndexer
{
    protected $indexerKey = '';
    protected $indexerName = '';

    /**
     * Registers the indexer configuration
     *
     * @param array $params
     * @param $pObj
     */
    public function registerIndexerConfiguration(&$params, $pObj)
    {
        if (!empty($this->indexerKey) && !empty($this->indexerName)) {
            $newArray = [
                $this->indexerName,
                $this->indexerKey,
                \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('cart_events') . 'ext_icon.svg'
            ];
            $params['items'][] = $newArray;
        }
    }

    /**
     * custom indexer for ke_search
     *
     * @param array $indexerConfig
     * @param array $indexerObject
     * @return string Output.
     */
    public function customIndexer(&$indexerConfig, &$indexerObject)
    {
        if ($indexerConfig['type'] === $this->indexerKey) {
            return $this->cartEventIndexer($indexerConfig, $indexerObject);
        }

        return '';
    }

    /**
     * cart indexer for ke_search
     *
     * @param array $indexerConfig
     * @param array $indexerObject
     *
     * @return string
     */
    abstract public function cartEventIndexer(&$indexerConfig, &$indexerObject);

    /**
     * Returns all Storage Pids for indexing
     *
     * @param $config
     *
     * @return string
     */
    protected function getPidList($config)
    {
        $recursivePids = $this->extendPidListByChildren($config['startingpoints_recursive'], 99);
        if ($config['sysfolder']) {
            return $recursivePids . ',' . $config['sysfolder'];
        }

        return $recursivePids;
    }

    /**
     * Find all ids from given ids and level
     *
     * @param string $pidList
     * @param int $recursive
     *
     * @return string
     */
    protected function extendPidListByChildren($pidList = '', $recursive = 0)
    {
        $recursive = (int)$recursive;

        if ($recursive <= 0) {
            return $pidList;
        }

        $queryGenerator = GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Database\QueryGenerator::class
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
     *
     * @param string $indexPids
     *
     * @return array
     */
    protected function getEventsToIndex($indexPids)
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

    /**
     *
     */
    protected function getTargetPidFormPage($eventUid)
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
    protected function getTargetPidFormCategory($categoryUid)
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
