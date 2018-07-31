<?php

defined('TYPO3_MODE') or die();

$iconPath = 'EXT:' . $_EXTKEY . '/Resources/Public/Icons/';

$_LLL = 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_be.xlf';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Configuration/TypoScript',
    'Shopping Cart - Cart Events'
);

/**
 * Register Frontend Plugins
 */
$pluginNames = [
    'Events' => [
        'pluginSignature' => 'select_key'
    ],
    'SingleEvent' => [
        'pluginSignature' => 'select_key, pages, recursive'
    ],
    'Slots' => [
        'pluginSignature' => 'select_key, recursive'
    ],
];

foreach ($pluginNames as $pluginName => $pluginConf) {
    $pluginSignature = strtolower(str_replace('_', '', $_EXTKEY)) . '_' . strtolower($pluginName);
    $pluginNameSC = strtolower(preg_replace('/[A-Z]/', '_$0', lcfirst($pluginName)));
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Extcode.' . $_EXTKEY,
        $pluginName,
        $_LLL . ':tx_cartevents.plugin.' . $pluginNameSC . '.title'
    );

    $TCA['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = $pluginConf['pluginSignature'];

    $flexFormPath = 'EXT:' . $_EXTKEY . '/Configuration/FlexForms/' . $pluginName . 'Plugin.xml';
    if (file_exists(\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($flexFormPath))) {
        $TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
            $pluginSignature,
            'FILE:' . $flexFormPath
        );
    }
}

$TCA['pages']['ctrl']['typeicon_classes']['contains-cartevents'] = 'apps-pagetree-folder-cartevents-events';

$TCA['pages']['columns']['module']['config']['items'][] = [
    'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_be.xlf:tcarecords-pages-contains.cart_events',
    'cartevents',
    'EXT:cart_events/Resources/Public/Icons/pagetree_cartevents_folder.svg',
];
