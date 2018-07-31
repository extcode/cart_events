<?php
defined('TYPO3_MODE') or die();

call_user_func(function () {
    $_LLL = 'LLL:EXT:cart_events/Resources/Private/Language/locallang_be.xlf';

    $GLOBALS['TCA']['pages']['columns']['doktype']['config']['items'][] = [
        $_LLL . ':pages.doktype.185',
        185,
        'apps-pagetree-page-cartevents-events'
    ];

    $GLOBALS['TCA']['pages']['ctrl']['typeicon_classes'][185] = 'apps-pagetree-page-cartevents-events';

    $GLOBALS['TCA']['pages']['columns']['doktype']['config']['items'][] = [
        $_LLL . ':pages.doktype.186',
        186,
        'apps-pagetree-page-cartevents-events'
    ];

    $GLOBALS['TCA']['pages']['ctrl']['typeicon_classes'][186] = 'apps-pagetree-page-cartevents-events';

    $newPagesColumns = [
        'cart_events_event' => [
            'displayCond' => 'FIELD:doktype:=:186',
            'exclude' => true,
            'label' => $_LLL . ':pages.singleview_cart_events_event',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_cartevents_domain_model_event',
                'foreign_table' => 'tx_cartevents_domain_model_event',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'pages',
        $newPagesColumns
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'pages',
        'standard',
        ',--linebreak--,cart_events_event',
        'after:doktype'
    );
});
