<?php

defined('TYPO3_MODE') or die();

$_LLL_general = 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf';
$_LLL = 'LLL:EXT:cart_events/Resources/Private/Language/locallang_db.xlf';

return [
    'ctrl' => [
        'title' => $_LLL . ':tx_cartevents_domain_model_calendarentry',
        'label' => 'begin',
        'label_alt' => 'end',
        'label_alt_force' => 1,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,

        'hideTable' => true,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'title',
        'iconfile' => 'EXT:cart_events/Resources/Public/Icons/tx_cartevents_domain_model_calendarentry.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, begin, end, note',
    ],
    'types' => [
        '1' => [
            'showitem' => 'hidden;;1, begin, end, note'
        ],
    ],
    'palettes' => [
        '1' => [
            'showitem' => ''
        ],
    ],
    'columns' => [
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
            'label' => $_LLL_general . ':LGL.endtime',
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

        'begin' => [
            'label' => $_LLL . ':tx_cartevents_domain_model_calendarentry.begin',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'required,datetime',
                'checkbox' => 0,
                'default' => 0,
            ],
        ],
        'end' => [
            'label' => $_LLL . ':tx_cartevents_domain_model_calendarentry.end',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
            ],
        ],

        'note' => [
            'exclude' => 1,
            'label' => $_LLL . ':tx_cartevents_domain_model_calendarentry.note',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ],
            'defaultExtras' => 'richtext[]'
        ],

        'event_date' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
