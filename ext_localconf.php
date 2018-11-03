<?php

defined('TYPO3_MODE') or die();

$_LLL_be = 'LLL:EXT:cart_events/Resources/Private/Language/locallang_be.xlf';

// configure plugins

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Extcode.' . $_EXTKEY,
    'Events',
    [
        'Event' => 'show, list',
    ],
    [
        'Event' => '',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Extcode.' . $_EXTKEY,
    'TeaserEvents',
    [
        'Event' => 'teaser',
    ],
    [
        'Event' => '',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Extcode.' . $_EXTKEY,
    'SingleEvent',
    [
        'Event' => 'show',
    ],
    [
        'Event' => '',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Extcode.' . $_EXTKEY,
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
    <INCLUDE_TYPOSCRIPT: source="FILE:EXT:cart_events/Configuration/TSconfig/ContentElementWizard.typoscript">
');

// Cart Hooks

if (TYPO3_MODE === 'FE') {
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cart']['CartEvents'] =
        \Extcode\CartEvents\Hooks\CartProductHook::class;
}

// realurl Hook

if (TYPO3_MODE === 'FE') {
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['ConfigurationReader_postProc'][1520842411] =
        'EXT:cart_events/Classes/Hooks/RealUrlHook.php:Extcode\CartEvents\Hooks\RealUrlHook->postProcessConfiguration';
}

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

// register listTemplateLayouts
$GLOBALS['TYPO3_CONF_VARS']['EXT'][$_EXTKEY]['templateLayouts']['events'][] = [$_LLL_be . ':flexforms_template.templateLayout.events.table', 'table'];
$GLOBALS['TYPO3_CONF_VARS']['EXT'][$_EXTKEY]['templateLayouts']['events'][] = [$_LLL_be . ':flexforms_template.templateLayout.events.grid', 'grid'];
$GLOBALS['TYPO3_CONF_VARS']['EXT'][$_EXTKEY]['templateLayouts']['teaser_events'][] = [$_LLL_be . ':flexforms_template.templateLayout.events.table', 'table'];
$GLOBALS['TYPO3_CONF_VARS']['EXT'][$_EXTKEY]['templateLayouts']['teaser_events'][] = [$_LLL_be . ':flexforms_template.templateLayout.events.grid', 'grid'];
$GLOBALS['TYPO3_CONF_VARS']['EXT'][$_EXTKEY]['templateLayouts']['event_dates'][] = [$_LLL_be . ':flexforms_template.templateLayout.event_dates.table', 'table'];
$GLOBALS['TYPO3_CONF_VARS']['EXT'][$_EXTKEY]['templateLayouts']['event_dates'][] = [$_LLL_be . ':flexforms_template.templateLayout.event_dates.grid', 'grid'];
$GLOBALS['TYPO3_CONF_VARS']['EXT'][$_EXTKEY]['templateLayouts']['single_event'][] = [$_LLL_be . ':flexforms_template.templateLayout.single_event.default', 'default'];
