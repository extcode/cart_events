<?php

defined('TYPO3_MODE') or die();

$_LLL_general = 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf';
$_LLL_ttc = 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf';
$_LLL_cart = 'LLL:EXT:cart/Resources/Private/Language/locallang_db.xlf';
$_LLL_db = 'LLL:EXT:cart_events/Resources/Private/Language/locallang_db.xlf';
$_LLL_tca = 'LLL:EXT:cart_events/Resources/Private/Language/locallang_tca.xlf';

return [
    'ctrl' => [
        'title' => $_LLL_db . ':tx_cartevents_domain_model_event',
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

        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'requestUpdate' => '',
        'searchFields' => 'sku,title,teaser,description,audience',
        'iconfile' => 'EXT:cart_events/Resources/Public/Icons/tx_cartevents_domain_model_event.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, sku, title, teaser, description, meta_description, tax_class_id, event_dates, related_events, category, categories, tags',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                sys_language_uid, l10n_parent, l10n_diffsource,
                sku, title, path_segment,
                --div--;' . $_LLL_tca . ':tx_cartevents_domain_model_event.div.descriptions,
                    teaser, description, audience,
                    meta_description,
                --div--;' . $_LLL_tca . ':tx_cartevents_domain_model_event.div.images_and_files,
                    images, files,
                --div--;' . $_LLL_tca . ':tx_cartevents_domain_model_event.div.event_dates,
                    tax_class_id,
                    event_dates,
                --div--;' . $_LLL_tca . ':tx_cartevents_domain_model_event.div.relations,
                    related_events, 
                --div--;' . $_LLL_tca . ':tx_cartevents_domain_model_event.div.categorization,
                    tags, category, categories,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.access,
                    --palette--;' . $_LLL_tca . ':palettes.visibility;hiddenonly,
                    --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.access;access,
            '
        ],
    ],
    'palettes' => [
        '1' => [
            'showitem' => ''
        ],
        'hiddenonly' => [
            'showitem' => 'hidden;' . $_LLL_db . ':tx_cartevents_domain_model_event',
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
                'eval' => 'int',
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
                'foreign_table' => 'tx_cartevents_domain_model_event',
                'foreign_table_where' => 'AND tx_cartevents_domain_model_event.pid=###CURRENT_PID### AND tx_cartevents_domain_model_event.sys_language_uid IN (-1,0)',
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
            'label' => $_LLL_db . ':tx_cartevents_domain_model_event.sku',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'required,trim'
            ],
        ],
        'title' => [
            'exclude' => 1,
            'label' => $_LLL_db . ':tx_cartevents_domain_model_event.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'required,trim'
            ],
        ],
        'path_segment' => [
            'exclude' => true,
            'label' => $_LLL_db . ':tx_cartevents_domain_model_event.path_segment',
            'config' => [
                'type' => 'slug',
                'size' => 50,
                'generatorOptions' => [
                    'fields' => ['title'],
                    'replacements' => [
                        '/' => '',
                    ],
                ],
                'fallbackCharacter' => '-',
                'eval' => 'uniqueInSite',
                'default' => '',
            ],
        ],

        'teaser' => [
            'exclude' => 1,
            'label' => $_LLL_db . ':tx_cartevents_domain_model_event.teaser',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ],
        ],
        'description' => [
            'exclude' => 1,
            'label' => $_LLL_db . ':tx_cartevents_domain_model_event.description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ],
        ],
        'meta_description' => [
            'exclude' => 1,
            'label' => $_LLL_db . ':tx_cartevents_domain_model_event.meta_description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
                'eval' => 'trim'
            ],
        ],
        'audience' => [
            'exclude' => 1,
            'label' => $_LLL_db . ':tx_cartevents_domain_model_event.audience',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ],
        ],

        'related_events' => [
            'exclude' => 1,
            'label' => $_LLL_db . ':tx_cartevents_domain_model_event.related_events',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_cartevents_domain_model_event',
                'foreign_table' => 'tx_cartevents_domain_model_event',
                'MM_opposite_field' => 'related_events_from',
                'size' => 5,
                'minitems' => 0,
                'maxitems' => 100,
                'MM' => 'tx_cartevents_domain_model_event_event_related_mm',
                'suggestOptions' => [
                    'default' => [
                        'searchWholePhrase' => true
                    ],
                ],
            ]
        ],

        'related_events_from' => [
            'exclude' => 1,
            'label' => $_LLL_db . ':tx_cartevents_domain_model_event.related_events_from',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'foreign_table' => 'tx_cartevents_domain_model_event',
                'allowed' => 'tx_cartevents_domain_model_event',
                'size' => 5,
                'maxitems' => 100,
                'MM' => 'tx_cartevents_domain_model_event_event_related_mm',
                'readOnly' => 1,
            ]
        ],

        'images' => [
            'exclude' => 1,
            'label' => $_LLL_db . ':tx_cartevents_domain_model_event.images',
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
                        'tablenames' => 'tx_cartevents_domain_model_event',
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
            'label' => $_LLL_db . ':tx_cartevents_domain_model_event.files',
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
                        'tablenames' => 'tx_cartevents_domain_model_event',
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

        'event_dates' => [
            'exclude' => 1,
            'label' => $_LLL_db . ':tx_cartevents_domain_model_event.event_dates',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_cartevents_domain_model_eventdate',
                'foreign_field' => 'event',
                'foreign_table_where' => ' AND tx_cartevents_domain_model_eventdate.pid=###CURRENT_PID### ORDER BY tx_cartevents_domain_model_eventdate.title ',
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
                'suggestOptions' => [
                    'default' => [
                        'searchWholePhrase' => true
                    ],
                ],
            ],
        ],
    ],
];
