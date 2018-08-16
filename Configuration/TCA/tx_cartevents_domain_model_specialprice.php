<?php

defined('TYPO3_MODE') or die();

$_LLL_general = 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf';
$_LLL = 'LLL:EXT:cart_events/Resources/Private/Language/locallang_db.xlf';

return [
    'ctrl' => [
        'title' => $_LLL . ':tx_cartevents_domain_model_specialprice',
        'label' => 'price',
        'label_alt' => 'starttime, endtime',
        'label_alt_force' => 1,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,

        'versioningWS' => 2,
        'versioning_followPages' => true,

        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'hideTable' => true,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
            'fe_group' => 'frontend_user_group',
        ],
        'searchFields' => 'price',
        'iconfile' => 'EXT:cart_events/Resources/Public/Icons/tx_cartevents_domain_model_specialprice.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, starttime, endtime, title, frontend_user_group, price',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                frontend_user_group, title, price,
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
            'showitem' => 'hidden;' . $_LLL . ':tx_cartevents_domain_model_specialprice',
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
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_cartevents_domain_model_specialprice',
                'foreign_table_where' => 'AND tx_cartevents_domain_model_specialprice.pid=###CURRENT_PID### AND tx_cartevents_domain_model_specialprice.sys_language_uid IN (-1,0)',
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
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => $_LLL_general . ':LGL.endtime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
            ],
        ],

        'title' => [
            'exclude' => 1,
            'label' => $_LLL . ':tx_cartevents_domain_model_specialprice.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'price' => [
            'exclude' => 1,
            'label' => $_LLL . ':tx_cartevents_domain_model_specialprice.price',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'required,double2',
                'default' => '0.00',
            ]
        ],

        'frontend_user_group' => [
            'exclude' => 1,
            'label' => $_LLL . ':tx_cartevents_domain_model_specialprice.frontend_user_group',
            'config' => [
                'type' => 'select',
                'readOnly' => 0,
                'foreign_table' => 'fe_groups',
                'size' => 1,
                'items' => [
                    ['', 0],
                ],
                'minitems' => 0,
                'maxitems' => 1,
            ]
        ],

        'event_date' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
