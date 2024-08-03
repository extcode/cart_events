<?php

defined('TYPO3') or die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$_LLL_db = 'LLL:EXT:cart_events/Resources/Private/Language/locallang_db.xlf:';

$newSysCategoryColumns = [
    'cart_event_list_pid' => [
        'exclude' => 1,
        'label' => $_LLL_db . 'tx_cartevents_domain_model_category.cart_event_list_pid',
        'config' => [
            'type' => 'group',
            'allowed' => 'pages',
            'size' => 1,
            'maxitems' => 1,
            'minitems' => 0,
            'default' => 0,
            'suggestOptions' => [
                'default' => [
                    'searchWholePhrase' => true,
                ],
            ],
        ],
    ],
    'cart_event_show_pid' => [
        'exclude' => 1,
        'label' => $_LLL_db . 'tx_cartevents_domain_model_category.cart_event_show_pid',
        'config' => [
            'type' => 'group',
            'allowed' => 'pages',
            'size' => 1,
            'maxitems' => 1,
            'minitems' => 0,
            'default' => 0,
            'suggestOptions' => [
                'default' => [
                    'searchWholePhrase' => true,
                ],
            ],
        ],
    ],
];

ExtensionManagementUtility::addTCAcolumns('sys_category', $newSysCategoryColumns);
ExtensionManagementUtility::addToAllTCAtypes(
    'sys_category',
    'cart_event_list_pid, cart_event_show_pid',
    '',
    'after:description'
);
