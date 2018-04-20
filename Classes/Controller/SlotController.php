<?php
declare(strict_types=1);

namespace Extcode\CartEvents\Controller;

use Extcode\CartEvents\Domain\Repository\SlotRepository;

/**
 * Slot Controller
 *
 * @author Daniel Lorenz <ext.cart@extco.de>
 */
class SlotController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @var SlotRepository
     */
    protected $slotRepository;

    /**
     * @param SlotRepository $slotRepository
     */
    public function injectSlotRepository(SlotRepository $slotRepository)
    {
        $this->slotRepository = $slotRepository;
    }

    protected function initializeAction()
    {
        if (!empty($GLOBALS['TSFE']) && is_object($GLOBALS['TSFE'])) {
            static $cacheTagsSet = false;

            /** @var $typoScriptFrontendController \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController */
            $typoScriptFrontendController = $GLOBALS['TSFE'];
            if (!$cacheTagsSet) {
                $typoScriptFrontendController->addCacheTags(['tx_cartevents']);
                $cacheTagsSet = true;
            }
        }
    }

    /**
     *
     */
    public function listAction()
    {
        if (!$this->settings) {
            $this->settings = [];
        }

        $slots = $this->slotRepository->findNext();

        $this->view->assign('slots', $slots);
    }
}
