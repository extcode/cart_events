<?php
declare(strict_types=1);

namespace Extcode\CartEvents\Controller;

use Extcode\CartEvents\Domain\Repository\EventDateRepository;

/**
 * @author Daniel Lorenz <ext.cart@extco.de>
 */
class EventDateController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @var EventDateRepository
     */
    protected $eventDateRepository;

    /**
     * @param EventDateRepository $eventDateRepository
     */
    public function injectEventDateRepository(EventDateRepository $eventDateRepository)
    {
        $this->eventDateRepository = $eventDateRepository;
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

        $eventDates = $this->eventDateRepository->findNext($limit, $pidList)->fetchAll();

        $this->view->assign('eventDates', $eventDates);

        $this->addCacheTags($eventDates);
    }

    /**
     * @param $eventDates
     */
    protected function addCacheTags($eventDates)
    {
        $cacheTags = [];

        if (!empty($GLOBALS['TSFE']) && is_object($GLOBALS['TSFE'])) {
            foreach ($eventDates as $eventDate) {
                $cacheTags[] = 'tx_cartevents_event_' . $eventDate['event'];
            }

            if (count($cacheTags) > 0) {
                $GLOBALS['TSFE']->addCacheTags($cacheTags);
            }
        }
    }
}
