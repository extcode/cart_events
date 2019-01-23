<?php

defined('TYPO3_MODE') or die();

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('ke_search')) {
    $GLOBALS['TCA']['tx_kesearch_indexerconfig']['columns']['startingpoints_recursive']['displayCond'] .=
        ',carteventsindexer,cartsingleeventindexer';

    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['registerIndexerConfiguration'][] =
        \Extcode\CartEvents\Hooks\KeSearchEventsIndexer::class;
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['customIndexer'][] =
        \Extcode\CartEvents\Hooks\KeSearchEventsIndexer::class;
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['registerIndexerConfiguration'][] =
        \Extcode\CartEvents\Hooks\KeSearchSingleEventIndexer::class;
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['customIndexer'][] =
        \Extcode\CartEvents\Hooks\KeSearchSingleEventIndexer::class;
}
