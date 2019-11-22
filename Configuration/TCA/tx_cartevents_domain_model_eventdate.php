<?php

defined('TYPO3_MODE') or die();

$_LLL_general = 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf';
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
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,

        'sortby' => 'sorting',

        'versioningWS' => true,

        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',

        'hideTable' => true,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'sku,title,',
        'iconfile' => 'EXT:cart_events/Resources/Public/Icons/tx_cartevents_domain_model_eventdate.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, sku, title, begin, end, note, calendar_entries, bookable, bookable_until, price, special_prices, handle_seats, seats_number, seats_taken',
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
                    bookable, bookable_until, price, special_prices, --linebreak--, handle_seats, seats_number, seats_taken,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.access,
                    --palette--;' . $_LLL_tca . ':palettes.visibility;hiddenonly,
                    --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.access;access,
            '
        ],
    ],
    'palettes' => [
        'begin_and_end' => [
            'showitem' => 'begin, end, --linebreak--, note'
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
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        $_LLL_general . ':LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ],
                ],
                'default' => 0,
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
                'foreign_table' => 'tx_cartevents_domain_model_eventdate',
                'foreign_table_where' => 'AND tx_cartevents_domain_model_eventdate.pid=###CURRENT_PID### AND tx_cartevents_domain_model_eventdate.sys_language_uid IN (-1,0)',
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
            'label' => $_LLL_general . ':LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
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
            'label' => 'LLL' . ':EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
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
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.sku',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'required,trim'
            ],
        ],

        'title' => [
            'exclude' => 1,
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'required,trim'
            ],
        ],

        'begin' => [
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.begin',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'required,datetime',
                'checkbox' => 0,
                'default' => 0,
            ],
        ],
        'end' => [
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.end',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
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
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ],
        ],

        'images' => [
            'exclude' => 1,
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.images',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'images',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => $_LLL_ttc . ':images.addFileReference',
                        'showPossibleLocalizationRecords' => true,
                        'showRemovedLocalizationRecords' => true,
                        'showAllLocalizationLink' => true,
                        'showSynchronizationLink' => true,
                    ],
                    'foreign_match_fields' => [
                        'fieldname' => 'image',
                        'tablenames' => 'tx_cartevents_domain_model_eventdate',
                        'table_local' => 'sys_file',
                    ],
                    // custom configuration for displaying fields in the overlay/reference table
                    // to use the imageoverlayPalette instead of the basicoverlayPalette
                    'overrideChildTca' => [
                        'types' => [
                            '0' => [
                                'showitem' => '
                                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                                'showitem' => '
                                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                                'showitem' => '
                                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                                'showitem' => '
                                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                                'showitem' => '
                                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                                'showitem' => '
                                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette'
                            ],
                        ],
                    ],
                    'minitems' => 0,
                    'maxitems' => 99,
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],

        'files' => [
            'exclude' => 1,
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.files',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'files',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => $_LLL_ttc . ':images.addFileReference',
                        'showPossibleLocalizationRecords' => true,
                        'showRemovedLocalizationRecords' => true,
                        'showAllLocalizationLink' => true,
                        'showSynchronizationLink' => true,
                    ],
                    'foreign_match_fields' => [
                        'fieldname' => 'files',
                        'tablenames' => 'tx_cartevents_domain_model_eventdate',
                        'table_local' => 'sys_file',
                    ],
                    // custom configuration for displaying fields in the overlay/reference table
                    // to use the imageoverlayPalette instead of the basicoverlayPalette
                    'overrideChildTca' => [
                        'types' => [
                            '0' => [
                                'showitem' => '
                                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                                'showitem' => '
                                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                                'showitem' => '
                                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                                'showitem' => '
                                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                                'showitem' => '
                                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                                'showitem' => '
                                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette'
                            ],
                        ],
                    ],
                    'minitems' => 0,
                    'maxitems' => 99,
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'] . ', pdf'
            ),
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
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ],
        ],

        'lecturer' => [
            'exclude' => 1,
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.lecturer',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
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
            ],
            'onChange' => 'reload',
        ],
        'bookable_until' => [
            'exclude' => 1,
            'displayCond' => 'FIELD:bookable:REQ:TRUE',
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.bookable_until',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
            ],
        ],

        'price' => [
            'exclude' => 1,
            'displayCond' => 'FIELD:bookable:REQ:TRUE',
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.price',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'required,double2',
                'default' => '0.00',
            ]
        ],

        'special_prices' => [
            'exclude' => 1,
            'displayCond' => 'FIELD:bookable:REQ:TRUE',
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
            'exclude' => 1,
            'displayCond' => 'FIELD:bookable:REQ:TRUE',
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.handle_seats',
            'config' => [
                'type' => 'check',
                'default' => true,
            ],
            'onChange' => 'reload',
        ],

        'seats_number' => [
            'exclude' => 1,
            'displayCond' => [
                'AND' => [
                    'FIELD:bookable:REQ:TRUE',
                    'FIELD:handle_seats:REQ:TRUE',
                ]
            ],
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.seats_number',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'int'
            ]
        ],

        'seats_taken' => [
            'exclude' => 1,
            'displayCond' => [
                'AND' => [
                    'FIELD:bookable:REQ:TRUE',
                    'FIELD:handle_seats:REQ:TRUE',
                ]
            ],
            'label' => $_LLL_db . ':tx_cartevents_domain_model_eventdate.seats_taken',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'int'
            ]
        ],
    ],
];
