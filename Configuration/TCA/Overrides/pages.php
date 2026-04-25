<?php

defined('TYPO3') or die();

use Extcode\CartEvents\Configuration\Constants;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

call_user_func(function () {
    $_LLL_be = 'LLL:EXT:cart_events/Resources/Private/Language/locallang_be.xlf';

    ArrayUtility::mergeRecursiveWithOverrule(
        $GLOBALS['TCA']['pages'],
        [
            'columns' => [
                'doktype' => [
                    'config' => [
                        'items' => [
                            1777099626 =>  [
                                'label' => $_LLL_be . ':pages.doktype.' . Constants::DOKTYPE_CARTEVENTS_EVENTS,
                                'value' => Constants::DOKTYPE_CARTEVENTS_EVENTS,
                                'icon' => 'apps-pagetree-page-cartevents-events',
                                'group' => 'default',
                            ],
                            1777099665 => [
                                'label' => $_LLL_be . ':pages.doktype.' . Constants::DOKTYPE_CARTEVENTS_EVENT,
                                'value' => Constants::DOKTYPE_CARTEVENTS_EVENT,
                                'icon' => 'apps-pagetree-page-cartevents-events',
                                'group' => 'default',
                            ],
                        ],
                    ],
                ],
                'module' => [
                    'config' => [
                        'items' => [
                            1777099708 => [
                                'label' => $_LLL_be . ':tcarecords-pages-contains.cart_events',
                                'value' => 'cartevents',
                                'icon' => 'apps-pagetree-folder-cartevents-events',
                                'group' => 'default',
                            ],
                        ],
                    ],
                ],
            ],
            'ctrl' => [
                'typeicon_classes' => [
                    Constants::DOKTYPE_CARTEVENTS_EVENTS => 'apps-pagetree-page-cartevents-events',
                    Constants::DOKTYPE_CARTEVENTS_EVENT => 'apps-pagetree-page-cartevents-events',
                    'contains-cartevents' => 'apps-pagetree-folder-cartevents-events',
                ],
            ],
        ]
    );

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
