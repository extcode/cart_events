<?php

defined('TYPO3') or die();

$_LLL_general = 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf';
$_LLL_ttc = 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf';
$_LLL_db = 'LLL:EXT:cart_events/Resources/Private/Language/locallang_db.xlf';
$_LLL_tca = 'LLL:EXT:cart_events/Resources/Private/Language/locallang_tca.xlf';

return [
    'ctrl' => [
        'title' => $_LLL_db . ':tx_cartevents_domain_model_eventdate',
        'label' => 'sku',
        'label_alt' => 'title',
        'label_alt_force' => 1,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',

        'sortby' => 'sorting',

        'versioningWS' => true,

        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'translationSource' => 'l10n_source',

        'hideTable' => true,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'sku,title,',
        'iconfile' => 'EXT:cart_events/Resources/Public/Icons/tx_cartevents_domain_model_eventdate.svg',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                sku, title,
                --div--;' . $_LLL_tca . ':tx_cartevents_domain_model_eventdate.div.informations,
                    --palette--;' . $_LLL_tca . ':tx_cartevents_domain_model_eventdate.palettes.begin_and_end;begin_and_end, calendar_entries,
                    location, lecturer,
                --div--;' . $_LLL_tca . ':tx_cartevents_domain_model_eventdate.div.images_and_files,
                    images, files,
                --div--;' . $_LLL_tca . ':tx_cartevents_domain_model_eventdate.div.order,
                    bookable, bookable_until, price_categorized, price_categories, price, special_prices, --linebreak--, handle_seats, handle_seats_in_price_category, seats_number, seats_taken,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.access,
                    --palette--;' . $_LLL_tca . ':palettes.visibility;hiddenonly,
                    --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.access;access,
            ',
        ],
    ],
    'palettes' => [
        'begin_and_end' => [
            'showitem' => 'begin, end, --linebreak--, note',
        ],
        'hiddenonly' => [
            'showitem' => 'hidden;' . $_LLL_db . ':tx_cartevents_domain_model_eventdate',
        ],
        'access' => [
            'showitem' => 'starttime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:starttime_formlabel, endtime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:endtime_formlabel',
        ],
    ],
    'columns' => [

        'sys_language_uid' => [
            'exclude' => 1,
            'label' => $_LLL_general . ':LGL.language',
            'config' => ['type' => 'language'],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => $_LLL_general . ':LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => '', 'value' => 0],
                ],
                'foreign_table' => 'tx_cartevents_domain_model_eventdate',
                'foreign_table_where' => 'AND tx_cartevents_domain_model_eventdate.pid=###CURRENT_PID### AND tx_cartevents_domain_model_eventdate.sys_language_uid IN (-1,0)',
                'fieldWizard' => [
                    'selectIcons' => [
                        'disabled' => true,
                    ],
                ],
                'default' => 0,
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
            ],
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => $_LLL_general . ':LGL.hidden',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'label' => $_LLL_general . ':LGL.starttime',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, (int)date('m'), (int)date('d'), (int)date('Y')),
                ],
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'label' => 'LLL' . ':EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, (int)date('m'), (int)date('d'), (int)date('Y')),
                ],
            ],
        ],

        'sku' => [
            'exclude' => 1,
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.sku',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'required' => true,
            ],
        ],

        'title' => [
            'exclude' => 1,
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'required' => true,
            ],
        ],

        'begin' => [
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.begin',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'checkbox' => 0,
                'default' => 0,
                'required' => true,
            ],
        ],
        'end' => [
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.end',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'checkbox' => 0,
                'default' => 0,
            ],
        ],
        'note' => [
            'exclude' => 1,
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.note',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
            ],
        ],

        'images' => [
            'exclude' => 1,
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.images',
            'config' => [
                //## !!! Watch out for fieldName different from columnName
                'type' => 'file',
                'appearance' => [
                    'createNewRelationLinkTitle' => $_LLL_ttc . ':images.addFileReference',
                    'showPossibleLocalizationRecords' => true,
                    'showRemovedLocalizationRecords' => true,
                    'showAllLocalizationLink' => true,
                    'showSynchronizationLink' => true,
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
                'allowed' => 'common-image-types',
            ],
        ],

        'files' => [
            'exclude' => 1,
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.files',
            'config' => [
                //## !!! Watch out for fieldName different from columnName
                'type' => 'file',
                'appearance' => [
                    'createNewRelationLinkTitle' => $_LLL_ttc . ':images.addFileReference',
                    'showPossibleLocalizationRecords' => true,
                    'showRemovedLocalizationRecords' => true,
                    'showAllLocalizationLink' => true,
                    'showSynchronizationLink' => true,
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
                'allowed' => 'common-media-types',
            ],
        ],

        'event' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],

        'location' => [
            'exclude' => 1,
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.location',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
            ],
        ],

        'lecturer' => [
            'exclude' => 1,
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.lecturer',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
            ],
        ],

        'calendar_entries' => [
            'exclude' => 1,
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.calendar_entries',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_cartevents_domain_model_calendarentry',
                'foreign_field' => 'event_date',
                'foreign_table_where' => ' AND tx_cartevents_domain_model_calendarentry.pid=###CURRENT_PID### ',
                'foreign_default_sortby' => 'begin',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1,
                ],
            ],
        ],

        'bookable' => [
            'exclude' => 1,
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.bookable',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
            ],
            'onChange' => 'reload',
        ],
        'bookable_until' => [
            'exclude' => 1,
            'displayCond' => 'FIELD:bookable:REQ:TRUE',
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.bookable_until',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'checkbox' => 0,
                'default' => 0,
            ],
        ],

        'price_categorized' => [
            'exclude' => 1,
            'displayCond' => 'FIELD:bookable:REQ:TRUE',
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.price_categorized',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
            ],
            'onChange' => 'reload',
        ],

        'price_categories' => [
            'exclude' => 1,
            'displayCond' => [
                'AND' => [
                    'FIELD:bookable:REQ:TRUE',
                    'FIELD:price_categorized:REQ:TRUE',
                ],
            ],
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.price_categories',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_cartevents_domain_model_pricecategory',
                'foreign_field' => 'event_date',
                'foreign_table_where' => ' AND tx_cartevents_domain_model_pricecategory.pid=###CURRENT_PID### ',
                'foreign_default_sortby' => 'price',
                'maxitems' => 99,
                'appearance' => [
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1,
                ],
            ],
        ],

        'price' => [
            'exclude' => 1,
            'displayCond' => [
                'AND' => [
                    'FIELD:bookable:REQ:TRUE',
                    'FIELD:price_categorized:REQ:FALSE',
                ],
            ],
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.price.gross',
            'config' => [
                'type' => 'number',
                'size' => 30,
                'default' => '0.00',
                'format' => 'decimal',
                'required' => true,
            ],
        ],

        'special_prices' => [
            'exclude' => 1,
            'displayCond' => [
                'AND' => [
                    'FIELD:bookable:REQ:TRUE',
                    'FIELD:price_categorized:REQ:FALSE',
                ],
            ],
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.special_prices',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_cartevents_domain_model_specialprice',
                'foreign_field' => 'event_date',
                'foreign_table_where' => ' AND tx_cartevents_domain_model_specialprice.pid=###CURRENT_PID### ',
                'foreign_default_sortby' => 'price',
                'maxitems' => 99,
                'appearance' => [
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1,
                ],
            ],
        ],

        'handle_seats' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'displayCond' => 'FIELD:bookable:REQ:TRUE',
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.handle_seats',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => true,
            ],
            'onChange' => 'reload',
        ],
        'handle_seats_in_price_category' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'displayCond' => [
                'AND' => [
                    'FIELD:bookable:REQ:TRUE',
                    'FIELD:price_categorized:REQ:TRUE',
                    'FIELD:handle_seats:REQ:TRUE',
                ],
            ],
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.handle_seats_in_price_category',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
            ],
            'onChange' => 'reload',
        ],

        'seats_number' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'displayCond' => [
                'AND' => [
                    'FIELD:bookable:REQ:TRUE',
                    'FIELD:handle_seats:REQ:TRUE',
                    'FIELD:handle_seats_in_price_category:REQ:FALSE',
                ],
            ],
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.seats_number',
            'config' => [
                'type' => 'number',
                'size' => 30,
            ],
        ],

        'seats_taken' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'displayCond' => [
                'AND' => [
                    'FIELD:bookable:REQ:TRUE',
                    'FIELD:handle_seats:REQ:TRUE',
                    'FIELD:handle_seats_in_price_category:REQ:FALSE',
                ],
            ],
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.seats_taken',
            'config' => [
                'type' => 'number',
                'size' => 30,
            ],
        ],
    ],
];
