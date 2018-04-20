<?php

namespace Extcode\CartEvents\Utility;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Utility class to get the settings from Extension Manager
 */
class EmConfiguration
{

    /**
     * Parses the extension settings.
     *
     * @return \Extcode\CartEvents\Domain\Model\Dto\EmConfiguration
     * @throws \Exception If the configuration is invalid.
     */
    public static function getSettings()
    {
        $configuration = self::parseSettings();
        require_once(ExtensionManagementUtility::extPath('cart_events') . 'Classes/Domain/Model/Dto/EmConfiguration.php');
        $settings = new \Extcode\CartEvents\Domain\Model\Dto\EmConfiguration($configuration);
        return $settings;
    }

    /**
     * Parse settings and return it as array
     *
     * @return array unserialized extconf settings
     */
    public static function parseSettings()
    {
        $settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['cart_events']);

        if (!is_array($settings)) {
            $settings = [];
        }
        return $settings;
    }
}
