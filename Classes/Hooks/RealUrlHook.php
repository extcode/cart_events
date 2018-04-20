<?php

namespace Extcode\CartEvents\Hooks;

use DmitryDulepov\Realurl\Configuration\ConfigurationReader;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * RealUrlHook
 *
 * @author Daniel Lorenz <ext.cart@extco.de>
 */
class RealUrlHook
{
    /**
     * @param array &$parameters
     * @param ConfigurationReader $configurationReader
     */
    public function postProcessConfiguration(&$parameters, ConfigurationReader $configurationReader)
    {
        if (!isset($parameters['configuration']['fixedPostVars']['carteventsShowEvent'])) {
            return;
        }

        if ($configurationReader->getMode() === ConfigurationReader::MODE_DECODE) {
            $targetPageId = $this->getTypoScriptFrontendController()->id;
            $pageRecord = $this->getTypoScriptFrontendController()->page;
        } else {
            $targetPageId = $parameters['urlParameters']['id'];
            $pageRepository = GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\Page\\PageRepository');
            $pageRecord = $pageRepository->getPage($parameters['urlParameters']['id']);
        }

        if ($pageRecord) {
            switch ((int)$pageRecord['doktype']) {
                case 185:
                    $parameters['configuration']['fixedPostVars'][$targetPageId] = 'carteventsShowEvent';
                    break;
            }
        }

        return;
    }

    /**
     * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    protected function getTypoScriptFrontendController()
    {
        return $GLOBALS['TSFE'];
    }
}
