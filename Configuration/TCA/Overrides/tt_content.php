<?php

defined('TYPO3') or die();

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

    foreach ($pluginNames as $pluginName => $pluginConfig) {
        $flexFormPath = 'EXT:cart_events/Configuration/FlexForms/' . $pluginName . 'Plugin.xml';
        if (file_exists(GeneralUtility::getFileAbsFileName($flexFormPath))) {
            $flexFormPath = 'FILE:' . $flexFormPath;
        } else {
            $flexFormPath = '';
        }

        ExtensionUtility::registerPlugin(
            'CartEvents',
            $pluginName,
            $pluginConfig['translationKeyPrefix'] . '.title',
            $pluginConfig['pluginIcon'],
            'cart',
            $pluginConfig['translationKeyPrefix'] . '.description',
            $flexFormPath
        );
    }
});
