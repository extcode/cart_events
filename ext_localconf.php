<?php

use Extcode\CartEvents\Controller\EventController;
use Extcode\CartEvents\Controller\EventDateController;
use Extcode\CartEvents\Domain\Finisher\Form\AddToCartFinisher;
use Extcode\CartEvents\Hooks\DataHandler;
use Extcode\CartEvents\Hooks\DatamapDataHandlerHook;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

(static function (string $extKey): void {
    $_LLL_be = 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_be.xlf:';

    if (is_array($GLOBALS['TYPO3_CONF_VARS'] ?? null) === false) {
        throw new \Exception('$GLOBALS[\'TYPO3_CONF_VARS\'] is not an array', 1774601240);
    }

    ArrayUtility::mergeRecursiveWithOverrule(
        $GLOBALS['TYPO3_CONF_VARS'],
        [
            'EXT' => [
                $extKey => [
                    'templateLayouts' => [
                        'list_events' => [
                            'table' => [$_LLL_be . 'flexforms_template.templateLayout.events.table', 'table'],
                            'grid' => [$_LLL_be . 'flexforms_template.templateLayout.events.grid', 'grid'],
                        ],
                        'teaser_events' => [
                            'table' => [$_LLL_be . 'flexforms_template.templateLayout.events.table', 'table'],
                            'grid' => [$_LLL_be . 'flexforms_template.templateLayout.events.grid', 'grid'],
                        ],
                        'event_dates' => [
                            'table' => [$_LLL_be . 'flexforms_template.templateLayout.event_dates.table', 'table'],
                            'grid' => [$_LLL_be . 'flexforms_template.templateLayout.event_dates.grid', 'grid'],
                        ],
                        'single_event' => [
                            'default' => [$_LLL_be . 'flexforms_template.templateLayout.single_event.default', 'default'],
                        ],
                    ],
                ],
            ],
            'EXTCONF' => [
                'cart' => [
                    'CartEvents' => [
                        'Form' => [
                            'AddToCartFinisher' => AddToCartFinisher::class,
                        ],
                    ],
                ],
            ],
            'SC_OPTIONS' => [
                't3lib/class.t3lib_tcemain.php' => [
                    'processDatamapClass' => [
                        'cartevents_allowed' => DatamapDataHandlerHook::class,
                    ],
                    'clearCachePostProc' => [
                        'cartevents_clearcache' => DataHandler::class . '->clearCachePostProc',
                    ],
                ],
            ],
            'SYS' => [
                'fluid' => [
                    'namespaces' => [
                        'cartevents' => [
                            1 => 'Extcode\\CartEvents\\ViewHelpers',
                        ],
                    ],
                ],
                'locallangXMLOverride' => [
                    'EXT:cart/Resources/Private/Language/locallang.xlf' => [
                        'EXT:cart_events/Resources/Private/Language/Overrides/cart/locallang.xlf',
                    ],
                    'de' => [
                        'EXT:cart/Resources/Private/Language/de.locallang.xlf' => [
                            'EXT:cart_events/Resources/Private/Language/Overrides/cart/de.locallang.xlf',
                        ],
                    ],
                ],
            ],
        ]
    );

    // configure plugins
    ExtensionUtility::configurePlugin(
        'cart_events',
        'ShowEvent',
        [
            EventController::class => 'show, form',
        ],
        [
            EventController::class => 'form',
        ]
    );

    ExtensionUtility::configurePlugin(
        'cart_events',
        'ListEvents',
        [
            EventController::class => 'list, show, form',
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

})('cart_events');
