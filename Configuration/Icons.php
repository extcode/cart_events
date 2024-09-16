<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
    'apps-pagetree-folder-cartevents-events' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:cart_events/Resources/Public/Icons/apps_pagetree_folder_cartevents_events.svg',
    ],
    'apps-pagetree-page-cartevents-events' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:cart_events/Resources/Public/Icons/apps_pagetree_page_cartevents_events.svg',
    ],
    'ext-cartevents-wizard-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:cart_events/Resources/Public/Icons/cartevents_plugin_wizard.svg',
    ],
];
