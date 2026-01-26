<?php

defined('TYPO3') or die();

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$_LLL_db = 'LLL:EXT:cart_events/Resources/Private/Language/locallang_db.xlf';
$_LLL_tca = 'LLL:EXT:cart_events/Resources/Private/Language/locallang_tca.xlf';

if (ExtensionManagementUtility::isLoaded('form')) {
    $temporaryColumns = [
        'form_definition' => [
            'exclude' => 1,
            'label' => $_LLL_db . ':tx_cartevents_domain_model_event.form_definition',
            'description' => $_LLL_tca . ':tx_cartevents_domain_model_event.form_definition.description',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => 'LLL:EXT:form/Resources/Private/Language/Database.xlf:tt_content.pi_flexform.formframework.selectPersistenceIdentifier', 'value' => ''],
                ],
                'itemsProcFunc' => 'Extcode\\Cart\\Hooks\\ItemsProcFunc->user_formDefinition',
                'itemsProcFuncConfig' => [
                    'prototypeName' => 'cart-events',
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

// category restriction based on settings in extension manager
$categoryRestrictionSetting = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('cart_events', 'categoryRestriction');

if ($categoryRestrictionSetting) {
    $categoryRestriction = '';
    $categoryRestriction = match ($categoryRestrictionSetting) {
        'current_pid' => ' AND sys_category.pid=###CURRENT_PID### ',
        'siteroot' => ' AND sys_category.pid IN (###SITEROOT###) ',
        'page_tsconfig' => ' AND sys_category.pid IN (###PAGE_TSCONFIG_IDLIST###) ',
        default => '',
    };

    // prepend category restriction at the beginning of foreign_table_where
    if (!empty($categoryRestriction)) {
        $GLOBALS['TCA']['tx_cartevents_domain_model_event']['columns']['category']['config']['foreign_table_where'] = $categoryRestriction
            . $GLOBALS['TCA']['tx_cartevents_domain_model_event']['columns']['category']['config']['foreign_table_where'];
        $GLOBALS['TCA']['tx_cartevents_domain_model_event']['columns']['categories']['config']['foreign_table_where'] = $categoryRestriction
            . $GLOBALS['TCA']['tx_cartevents_domain_model_event']['columns']['categories']['config']['foreign_table_where'];
    }
}
