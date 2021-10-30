<?php

defined('TYPO3_MODE') or die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

call_user_func(function () {
    $_LLL_be = 'LLL:EXT:cart_events/Resources/Private/Language/locallang_be.xlf:';

    $pluginNames = [
        'Events' => [
            'subtypes_excludelist' => 'select_key'
        ],
        'TeaserEvents' => [
            'subtypes_excludelist' => 'select_key, pages, recursive'
        ],
        'SingleEvent' => [
            'subtypes_excludelist' => 'select_key, pages, recursive'
        ],
        'EventDates' => [
            'subtypes_excludelist' => 'select_key, recursive'
        ],
    ];

    foreach ($pluginNames as $pluginName => $pluginConf) {
        $pluginSignature = 'cartevents_' . strtolower($pluginName);
        $pluginNameSC = strtolower(preg_replace('/[A-Z]/', '_$0', lcfirst($pluginName)));
        ExtensionUtility::registerPlugin(
            'CartEvents',
            $pluginName,
            $_LLL_be . 'tx_cartevents.plugin.' . $pluginNameSC . '.title'
        );

        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = $pluginConf['subtypes_excludelist'];

        $flexFormPath = 'EXT:cart_events/Configuration/FlexForms/' . $pluginName . 'Plugin.xml';
        if (file_exists(GeneralUtility::getFileAbsFileName($flexFormPath))) {
            $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
            ExtensionManagementUtility::addPiFlexFormValue(
                $pluginSignature,
                'FILE:' . $flexFormPath
            );
        }
    }
});
