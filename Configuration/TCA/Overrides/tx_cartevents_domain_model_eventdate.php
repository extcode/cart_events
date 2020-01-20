<?php

defined('TYPO3_MODE') or die();

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$_LLL_db = 'LLL:EXT:cart_events/Resources/Private/Language/locallang_db.xlf';

$inputIsNetPrice = GeneralUtility::makeInstance(ExtensionConfiguration::class)
    ->get('cart_events', 'inputIsNetPrice');

if ((bool)$inputIsNetPrice) {
    $GLOBALS['TCA']['tx_cartevents_domain_model_eventdate']['columns']['price']['label'] = $_LLL_db . ':tx_cartevents_domain_model_eventdate.price.net';
}
