<?php

defined('TYPO3_MODE') or die();

// Extension manager configuration
$configuration = \Extcode\CartEvents\Utility\EmConfiguration::getSettings();

$_LLL_db = 'LLL:EXT:cart_events/Resources/Private/Language/locallang_db.xlf:';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::makeCategorizable(
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

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::makeCategorizable(
    'cart_events',
    'tx_cartevents_domain_model_event',
    'categories',
    [
        'label' => $_LLL_db . 'tx_cartevents_domain_model_event.categories'
    ]
);

// category restriction based on settings in extension manager
$categoryRestrictionSetting = $configuration->getCategoryRestriction();

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
