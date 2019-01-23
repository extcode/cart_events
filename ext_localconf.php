<?php

defined('TYPO3_MODE') or die();

$_LLL_be = 'LLL:EXT:cart_events/Resources/Private/Language/locallang_be.xlf:';

// configure plugins

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Extcode.cart_events',
    'Events',
    [
        'Event' => 'show, list, form',
    ],
    [
        'Event' => 'form',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Extcode.cart_events',
    'TeaserEvents',
    [
        'Event' => 'teaser',
    ],
    [
        'Event' => '',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Extcode.cart_events',
    'SingleEvent',
    [
        'Event' => 'show, form',
    ],
    [
        'Event' => 'form',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Extcode.cart_events',
    'EventDates',
    [
        'EventDate' => 'list',
    ],
    [
        'EventDate' => '',
    ]
);

// Icon Registry

if (TYPO3_MODE === 'BE') {
    $icons = [
        'apps-pagetree-folder-cartevents-events' => 'apps_pagetree_folder_cartevents_events.svg',
        'apps-pagetree-page-cartevents-events' => 'apps_pagetree_page_cartevents_events.svg',
        'ext-cartevents-wizard-icon' => 'cartevents_plugin_wizard.svg',
    ];

    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        \TYPO3\CMS\Core\Imaging\IconRegistry::class
    );

    foreach ($icons as $identifier => $fileName) {
        $iconRegistry->registerIcon(
            $identifier,
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            [
                'source' => 'EXT:cart_events/Resources/Public/Icons/' . $fileName,
            ]
        );
    }
}

// TSconfig

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
    <INCLUDE_TYPOSCRIPT: source="FILE:EXT:cart_events/Configuration/TSconfig/ContentElementWizard.tsconfig">
');

// Cart Hooks

if (TYPO3_MODE === 'FE') {
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cart']['CartEvents']['Cart']['AddToCartFinisher'] =
        \Extcode\CartEvents\Domain\Finisher\Cart\AddToCartFinisher::class;
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cart']['CartEvents']['Form']['AddToCartFinisher'] =
        \Extcode\CartEvents\Domain\Finisher\Form\AddToCartFinisher::class;
}

// ke_search Hook - register indexer for events

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['registerIndexerConfiguration'][] =
    \Extcode\CartEvents\Hooks\KeSearchEventsIndexer::class;
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['customIndexer'][] =
    \Extcode\CartEvents\Hooks\KeSearchEventsIndexer::class;
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['registerIndexerConfiguration'][] =
    \Extcode\CartEvents\Hooks\KeSearchSingleEventIndexer::class;
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['customIndexer'][] =
    \Extcode\CartEvents\Hooks\KeSearchSingleEventIndexer::class;

// processDatamapClass Hook

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['cartevents_allowed'] =
    \Extcode\CartEvents\Hooks\DatamapDataHandlerHook::class;

// clearCachePostProc Hook

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']['cartevents_clearcache'] =
    \Extcode\CartEvents\Hooks\DataHandler::class . '->clearCachePostProc';

// Signal Slots

$dispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class
);

$dispatcher->connect(
    \Extcode\Cart\Utility\StockUtility::class,
    'handleStock',
    \Extcode\CartEvents\Utility\StockUtility::class,
    'handleStock'
);

// register "cartevents:" namespace
$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['cartevents'][]
    = 'Extcode\\CartEvents\\ViewHelpers';

// update wizard for slugs
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['cartEventsSlugUpdater'] =
    \Extcode\CartEvents\Updates\SlugUpdater::class;

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
