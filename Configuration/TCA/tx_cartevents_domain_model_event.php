<?php

defined('TYPO3_MODE') or die();

$_LLL_general = 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf';
$_LLL_cart = 'LLL:EXT:cart/Resources/Private/Language/locallang_db.xlf';
$_LLL = 'LLL:EXT:cart_events/Resources/Private/Language/locallang_db.xlf';

return [
    'ctrl' => [
        'title' => $_LLL . ':tx_cartevents_domain_model_event',
        'label' => 'sku',
        'label_alt' => 'title',
        'label_alt_force' => 1,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,

        'sortby' => 'sorting',

        'versioningWS' => 2,
        'versioning_followPages' => true,

        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',

        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'requestUpdate' => '',
        'searchFields' => 'sku,title,teaser,description,audience',
        'iconfile' => 'EXT:cart_events/Resources/Public/Icons/Event.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, sku, title, teaser, description, meta_description, tax_class_id, slots, category, categories, tags',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource,
                sku, title,
                --div--;LLL:EXT:cart_events/Resources/Private/Language/locallang_tca.xlf:tx_cartevents_domain_model_event.div.descriptions,
                    teaser;;;richtext:rte_transform[mode=ts_links],
                    description;;;richtext:rte_transform[mode=ts_links], audience;;;richtext:rte_transform[mode=ts_links],
                    meta_description,
                --div--;LLL:EXT:cart_events/Resources/Private/Language/locallang_tca.xlf:tx_cartevents_domain_model_event.div.slots,
                    tax_class_id,
                    slots,
                --div--;LLL:EXT:cart_events/Resources/Private/Language/locallang_tca.xlf:tx_cartevents_domain_model_event.div.categorization,
                    tags, category, categories,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.access,
                    --palette--;LLL:EXT:cart_events/Resources/Private/Language/locallang_tca.xlf:palettes.visibility;hiddenonly,
                    --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.access;access,
            '
        ],
    ],
    'palettes' => [
        '1' => [
            'showitem' => ''
        ],
        'hiddenonly' => [
            'showitem' => 'hidden;' . $_LLL . ':tx_cartevents_domain_model_event',
        ],
        'access' => [
            'showitem' => 'starttime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:starttime_formlabel, endtime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:endtime_formlabel',
        ],
    ],
    'columns' => [

        'sys_language_uid' => [
            'exclude' => 1,
            'label' => $_LLL_general . ':LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    [$_LLL_general . ':LGL.allLanguages', -1],
                    [$_LLL_general . ':LGL.default_value', 0]
                ],
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => $_LLL_general . ':LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_cartevents_domain_model_event',
                'foreign_table_where' => 'AND tx_cartevents_domain_model_event.pid=###CURRENT_PID### AND tx_cartevents_domain_model_event.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => $_LLL_general . ':LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ]
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => $_LLL_general . ':LGL.hidden',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:hidden.I.0'
                    ]
                ]
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => $_LLL_general . ':LGL.starttime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL' . ':EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],

        'sku' => [
            'exclude' => 1,
            'label' => $_LLL . ':tx_cartevents_domain_model_event.sku',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'required,trim'
            ],
        ],
        'title' => [
            'exclude' => 1,
            'label' => $_LLL . ':tx_cartevents_domain_model_event.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'required,trim'
            ],
        ],
        'teaser' => [
            'exclude' => 1,
            'label' => $_LLL . ':tx_cartevents_domain_model_event.teaser',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ],
            'defaultExtras' => 'richtext[]'
        ],
        'description' => [
            'exclude' => 1,
            'label' => $_LLL . ':tx_cartevents_domain_model_event.description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ],
            'defaultExtras' => 'richtext[]'
        ],
        'meta_description' => [
            'exclude' => 1,
            'label' => $_LLL . ':tx_cartevents_domain_model_event.meta_description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
                'eval' => 'trim'
            ],
        ],
        'audience' => [
            'exclude' => 1,
            'label' => $_LLL . ':tx_cartevents_domain_model_event.audience',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ],
            'defaultExtras' => 'richtext[]'
        ],

        'tax_class_id' => [
            'exclude' => 1,
            'label' => $_LLL_cart . ':tx_cart.tax_class_id',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [$_LLL_cart . ':tx_cart.tax_class_id.1', 1],
                    [$_LLL_cart . ':tx_cart.tax_class_id.2', 2],
                    [$_LLL_cart . ':tx_cart.tax_class_id.3', 3],
                ],
                'size' => 1,
                'minitems' => 1,
                'maxitems' => 1,
            ],
        ],

        'slots' => [
            'exclude' => 1,
            'label' => $_LLL . ':tx_cartevents_domain_model_event.slots',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_cartevents_domain_model_slot',
                'foreign_field' => 'event',
                'foreign_table_where' => ' AND tx_cartevents_domain_model_slot.pid=###CURRENT_PID### ORDER BY tx_cartevents_domain_model_slot.title ',
                'foreign_sortby' => 'sorting',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1,
                    'enabledControls' => [
                        'info' => true,
                        'new' => true,
                        'dragdrop' => false,
                        'sort' => true,
                        'hide' => true,
                        'delete' => true,
                        'localize' => true,
                    ]
                ],
            ],
        ],

        'tags' => [
            'exclude' => 1,
            'label' => $_LLL_cart . ':tx_cart_domain_model_tags',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_cart_domain_model_tag',
                'foreign_table' => 'tx_cart_domain_model_tag',
                'size' => 5,
                'minitems' => 0,
                'maxitems' => 100,
                'MM' => 'tx_cartevents_domain_model_event_tag_mm',
                'wizards' => [
                    'suggest' => [
                        'type' => 'suggest',
                        'default' => [
                            'searchWholePhrase' => true
                        ]
                    ],
                ],
            ],
        ],
    ],
];
