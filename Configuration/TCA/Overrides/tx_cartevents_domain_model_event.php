<?php
defined('TYPO3_MODE') or die();

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$_LLL_db = 'LLL:EXT:cart_events/Resources/Private/Language/locallang_db.xlf:';

if (ExtensionManagementUtility::isLoaded('form')) {
    $temporaryColumns = [
        'form_definition' => [
            'exclude' => 1,
            'label' => $_LLL_db . ':tx_cartevents_domain_model_event.form_definition',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:form/Resources/Private/Language/Database.xlf:tt_content.pi_flexform.formframework.selectPersistenceIdentifier', ''],
                ],
                'itemsProcFunc' => 'Extcode\\Cart\\Hooks\\ItemsProcFunc->user_formDefinition',
                'itemsProcFuncConfig' => [
                    'prototypeName' => 'cart-events'
                ],
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'softref' => 'formPersistenceIdentifier',
            ],
        ],
    ];

    ExtensionManagementUtility::addTCAcolumns(
        'tx_cartevents_domain_model_event',
        $temporaryColumns
    );
    ExtensionManagementUtility::addToAllTCAtypes(
        'tx_cartevents_domain_model_event',
        'form_definition',
        '',
        'after:path_segment'
    );
}

ExtensionManagementUtility::makeCategorizable(
    'cart_events',
    'tx_cartevents_domain_model_event',
    'category',
    [
        'label' => $_LLL_db . 'tx_cartevents_domain_model_event.category',
        'fieldConfiguration' => [
            'minitems' => 0,
            'maxitems' => 1,
            'multiple' => false,
        ]
    ]
);

$GLOBALS['TCA']['tx_cartevents_domain_model_event']['category']['config']['maxitems'] = 1;

ExtensionManagementUtility::makeCategorizable(
    'cart_events',
    'tx_cartevents_domain_model_event',
    'categories',
    [
        'label' => $_LLL_db . 'tx_cartevents_domain_model_event.categories'
    ]
);

// category restriction based on settings in extension manager
$categoryRestrictionSetting = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('cart_events', 'categoryRestriction');

if ($categoryRestrictionSetting) {
    $categoryRestriction = '';
    switch ($categoryRestrictionSetting) {
        case 'current_pid':
            $categoryRestriction = ' AND sys_category.pid=###CURRENT_PID### ';
            break;
        case 'siteroot':
            $categoryRestriction = ' AND sys_category.pid IN (###SITEROOT###) ';
            break;
        case 'page_tsconfig':
            $categoryRestriction = ' AND sys_category.pid IN (###PAGE_TSCONFIG_IDLIST###) ';
            break;
        default:
            $categoryRestriction = '';
    }

    // prepend category restriction at the beginning of foreign_table_where
    if (!empty($categoryRestriction)) {
        $GLOBALS['TCA']['tx_cartevents_domain_model_event']['columns']['category']['config']['foreign_table_where'] = $categoryRestriction .
            $GLOBALS['TCA']['tx_cartevents_domain_model_event']['columns']['category']['config']['foreign_table_where'];
        $GLOBALS['TCA']['tx_cartevents_domain_model_event']['columns']['categories']['config']['foreign_table_where'] = $categoryRestriction .
            $GLOBALS['TCA']['tx_cartevents_domain_model_event']['columns']['categories']['config']['foreign_table_where'];
    }
}
