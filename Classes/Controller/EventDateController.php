<?php
declare(strict_types=1);
namespace Extcode\CartEvents\Controller;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\CartEvents\Domain\Repository\EventDateRepository;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class EventDateController extends ActionController
{
    /**
     * @var EventDateRepository
     */
    protected $eventDateRepository;

    public function injectEventDateRepository(EventDateRepository $eventDateRepository): void
    {
        $this->eventDateRepository = $eventDateRepository;
    }

    protected function initializeAction(): void
    {
        if (!empty($GLOBALS['TSFE']) && is_object($GLOBALS['TSFE'])) {
            static $cacheTagsSet = false;

            if (!$cacheTagsSet) {
                $GLOBALS['TSFE']->addCacheTags(['tx_cartevents']);
                $cacheTagsSet = true;
            }
        }
    }

    public function listAction(): void
    {
        if (!$this->settings) {
            $this->settings = [];
        }

        $limit = (int)$this->settings['limit'] ?: (int)$this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'CartEvents'
        )['view']['list']['limit'];

        $pidList = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
        )['persistence']['storagePid'];

        $eventDates = $this->eventDateRepository->findNext($limit, $pidList)->fetchAll();

        $this->view->assign('eventDates', $eventDates);

        $this->addCacheTags($eventDates);
    }

    protected function addCacheTags(iterable $eventDates): void
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
