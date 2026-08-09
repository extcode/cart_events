<?php

defined('TYPO3') or die();

use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

if (ExtensionManagementUtility::isLoaded('ke_search')) {
    $displayCond = 'carteventsindexer,cartsingleeventindexer';
    if (is_string($GLOBALS['TCA']['tx_kesearch_indexerconfig']['columns']['startingpoints_recursive']['displayCond'] ?? null)) {
        $displayCond = $GLOBALS['TCA']['tx_kesearch_indexerconfig']['columns']['startingpoints_recursive']['displayCond'] . ',' . $displayCond;
    }

    ArrayUtility::mergeRecursiveWithOverrule(
        $GLOBALS['TCA']['tx_kesearch_indexerconfig'],
        [
            'columns' => [
                'startingpoints_recursive' => [
                    'displayCond' => $displayCond,
                ],
            ],
        ]
    );
}
