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

            if (!$cacheTagsSet) {
                $GLOBALS['TSFE']->addCacheTags(['tx_cartevents']);
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

        $limit = (int)$this->settings['limit'] ?: (int)$this->configurationManager->getConfiguration(
                \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
                'CartEvents'
            )['view']['list']['limit'];

        $pidList = $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
        )['persistence']['storagePid'];

        $nextSlots = $this->slotRepository->findNext($limit, $pidList)->fetchAll();

        $slots = [];
        foreach ($nextSlots as $nextSlot) {
            $slots[] = $this->slotRepository->findByUid($nextSlot['uid']);
        }

        $this->view->assign('slots', $slots);
        $this->view->assign('events', $nextSlots);
    }

    /**
     * @param $slots
     */
    protected function addCacheTags($slots)
    {
        $cacheTags = [];

        if (!empty($GLOBALS['TSFE']) && is_object($GLOBALS['TSFE'])) {
            foreach ($slots as $slot) {
                $cacheTags[] = 'tx_cartevents_event_' . $slot['event_uid'];
            }

            if (count($cacheTags) > 0) {
                $GLOBALS['TSFE']->addCacheTags($cacheTags);
            }
        }
    }
}
