<?php
defined('TYPO3_MODE') or die();

call_user_func(function () {
    $_LLL = 'LLL:EXT:cart_events/Resources/Private/Language/locallang_be.xlf';

    $GLOBALS['TCA']['pages']['columns']['doktype']['config']['items'][] = [
        $_LLL . ':pages.doktype.185',
        185,
        'icon-apps-pagetree-cartevents-page'
    ];

    $GLOBALS['TCA']['pages']['ctrl']['typeicon_classes'][185] = 'icon-apps-pagetree-cartevents-page';
});
