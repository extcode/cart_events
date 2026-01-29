<?php

defined('TYPO3') or die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

call_user_func(function () {
    $_LLL_be = 'LLL:EXT:cart_events/Resources/Private/Language/locallang_be.xlf:';

    $pluginNames = [
        'ShowEvent' => [
            'pluginIcon' => 'ext-cartevents-wizard-icon-show',
            'translationKeyPrefix' => $_LLL_be . 'tx_cartevents.plugin.show_event',
        ],
        'ListEvents' => [
            'additionalNewFields' => 'pages, recursive',
            'pluginIcon' => 'ext-cartevents-wizard-icon-list',
            'translationKeyPrefix' => $_LLL_be . 'tx_cartevents.plugin.list_events',
        ],
        'TeaserEvents' => [
            'pluginIcon' => 'ext-cartevents-wizard-icon-teaser',
            'translationKeyPrefix' => $_LLL_be . 'tx_cartevents.plugin.teaser_events',
        ],
        'SingleEvent' => [
            'pluginIcon' => 'ext-cartevents-wizard-icon-show',
            'translationKeyPrefix' => $_LLL_be . 'tx_cartevents.plugin.single_event',
        ],
        'EventDates' => [
            'pluginIcon' => 'ext-cartevents-wizard-icon-show',
            'translationKeyPrefix' => $_LLL_be . 'tx_cartevents.plugin.event_dates',
        ],
    ];

    foreach ($pluginNames as $pluginName => $pluginConf) {
        $pluginSignature = ExtensionUtility::registerPlugin(
            'cart_events',
            $pluginName,
            $pluginConf['translationKeyPrefix'] . '.title',
            $pluginConf['pluginIcon'],
            'cart',
            $pluginConf['translationKeyPrefix'] . '.description',
        );

        $flexFormPath = 'EXT:cart_events/Configuration/FlexForms/' . $pluginName . 'Plugin.xml';
        if (file_exists(GeneralUtility::getFileAbsFileName($flexFormPath))) {
            ExtensionManagementUtility::addToAllTCAtypes(
                'tt_content',
                rtrim('--div--;Configuration,pi_flexform,' . ($pluginConf['additionalNewFields'] ?? ''), ','),
                $pluginSignature,
                'after:subheader',
            );

            ExtensionManagementUtility::addPiFlexFormValue(
                '*',
                'FILE:' . $flexFormPath,
                $pluginSignature,
            );
        }
    }
});
