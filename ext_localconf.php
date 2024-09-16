<?php

use Extcode\CartEvents\Controller\EventController;
use Extcode\CartEvents\Controller\EventDateController;
use Extcode\CartEvents\Domain\Finisher\Form\AddToCartFinisher;
use Extcode\CartEvents\Hooks\DataHandler;
use Extcode\CartEvents\Hooks\DatamapDataHandlerHook;
use Extcode\CartEvents\Updates\SlugUpdater;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

$_LLL_be = 'LLL:EXT:cart_events/Resources/Private/Language/locallang_be.xlf:';

// configure plugins

ExtensionUtility::configurePlugin(
    'cart_events',
    'Events',
    [
        EventController::class => 'show, list, form',
    ],
    [
        EventController::class => 'form',
    ]
);

ExtensionUtility::configurePlugin(
    'cart_events',
    'TeaserEvents',
    [
        EventController::class => 'teaser',
    ],
    [
        EventController::class => '',
    ]
);

ExtensionUtility::configurePlugin(
    'cart_events',
    'SingleEvent',
    [
        EventController::class => 'show, form',
    ],
    [
        EventController::class => 'form',
    ]
);

ExtensionUtility::configurePlugin(
    'cart_events',
    'EventDates',
    [
        EventDateController::class => 'list',
    ],
    [
        EventDateController::class => '',
    ]
);

// TSconfig

ExtensionManagementUtility::addPageTSConfig('
    <INCLUDE_TYPOSCRIPT: source="FILE:EXT:cart_events/Configuration/TSconfig/ContentElementWizard.tsconfig">
');

// Cart Hooks

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cart']['CartEvents']['Form']['AddToCartFinisher'] =
    AddToCartFinisher::class;

// processDatamapClass Hook

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['cartevents_allowed'] =
    DatamapDataHandlerHook::class;

// clearCachePostProc Hook

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']['cartevents_clearcache'] =
    DataHandler::class . '->clearCachePostProc';

// register "cartevents:" namespace
$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['cartevents'][]
    = 'Extcode\\CartEvents\\ViewHelpers';

// update wizard for slugs
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['cartEventsSlugUpdater'] =
    SlugUpdater::class;

// translation overrides

$GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['EXT:cart/Resources/Private/Language/locallang.xlf'][] = 'EXT:cart_events/Resources/Private/Language/Overrides/cart/locallang.xlf';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['de']['EXT:cart/Resources/Private/Language/de.locallang.xlf'][] = 'EXT:cart_events/Resources/Private/Language/Overrides/cart/de.locallang.xlf';

// register listTemplateLayouts
$GLOBALS['TYPO3_CONF_VARS']['EXT']['cart_events']['templateLayouts']['events'][] = [$_LLL_be . 'flexforms_template.templateLayout.events.table', 'table'];
$GLOBALS['TYPO3_CONF_VARS']['EXT']['cart_events']['templateLayouts']['events'][] = [$_LLL_be . 'flexforms_template.templateLayout.events.grid', 'grid'];
$GLOBALS['TYPO3_CONF_VARS']['EXT']['cart_events']['templateLayouts']['teaser_events'][] = [$_LLL_be . 'flexforms_template.templateLayout.events.table', 'table'];
$GLOBALS['TYPO3_CONF_VARS']['EXT']['cart_events']['templateLayouts']['teaser_events'][] = [$_LLL_be . 'flexforms_template.templateLayout.events.grid', 'grid'];
$GLOBALS['TYPO3_CONF_VARS']['EXT']['cart_events']['templateLayouts']['event_dates'][] = [$_LLL_be . 'flexforms_template.templateLayout.event_dates.table', 'table'];
$GLOBALS['TYPO3_CONF_VARS']['EXT']['cart_events']['templateLayouts']['event_dates'][] = [$_LLL_be . 'flexforms_template.templateLayout.event_dates.grid', 'grid'];
$GLOBALS['TYPO3_CONF_VARS']['EXT']['cart_events']['templateLayouts']['single_event'][] = [$_LLL_be . 'flexforms_template.templateLayout.single_event.default', 'default'];
