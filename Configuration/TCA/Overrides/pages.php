<?php

defined('TYPO3') or die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

call_user_func(function () {
    $_LLL_be = 'LLL:EXT:cart_events/Resources/Private/Language/locallang_be.xlf';

    $GLOBALS['TCA']['pages']['columns']['doktype']['config']['items'][] = [
        'label' => $_LLL_be . ':pages.doktype.185',
        'value' => 185,
        'icon' => 'apps-pagetree-page-cartevents-events',
    ];
    $GLOBALS['TCA']['pages']['columns']['doktype']['config']['items'][] = [
        'label' => $_LLL_be . ':pages.doktype.186',
        'value' => 186,
        'icon' => 'apps-pagetree-page-cartevents-events',
    ];
    $GLOBALS['TCA']['pages']['columns']['module']['config']['items'][] = [
        'label' => $_LLL_be . ':tcarecords-pages-contains.cart_events',
        'value' => 'cartevents',
        'icon' => 'apps-pagetree-folder-cartevents-events',
    ];

    $GLOBALS['TCA']['pages']['ctrl']['typeicon_classes'][185] = 'apps-pagetree-page-cartevents-events';
    $GLOBALS['TCA']['pages']['ctrl']['typeicon_classes'][186] = 'apps-pagetree-page-cartevents-events';
    $GLOBALS['TCA']['pages']['ctrl']['typeicon_classes']['contains-cartevents'] = 'apps-pagetree-folder-cartevents-events';

    $newPagesColumns = [
        'cart_events_event' => [
            'displayCond' => 'FIELD:doktype:=:186',
            'exclude' => true,
            'label' => $_LLL_be . ':pages.singleview_cart_events_event',
            'config' => [
                'type' => 'group',
                'allowed' => 'tx_cartevents_domain_model_event',
                'foreign_table' => 'tx_cartevents_domain_model_event',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
    ];

    ExtensionManagementUtility::addTCAcolumns(
        'pages',
        $newPagesColumns
    );

    ExtensionManagementUtility::addFieldsToPalette(
        'pages',
        'standard',
        ',--linebreak--,cart_events_event',
        'after:doktype'
    );
});
