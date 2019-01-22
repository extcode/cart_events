<?php
declare(strict_types=1);

namespace Extcode\CartEvents\Controller;

use Extcode\CartEvents\Domain\Model\Dto\EventDemand;
use Extcode\CartEvents\Domain\Repository\EventRepository;

/**
 * @author Daniel Lorenz <ext.cart@extco.de>
 */
class EventController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * Cart Utility
     *
     * @var \Extcode\Cart\Utility\CartUtility
     */
    protected $cartUtility;

    /**
     * @var EventRepository
     */
    protected $eventRepository;

    /**
     * categoryRepository
     *
     * @var \Extcode\CartEvents\Domain\Repository\CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var array
     */
    protected $cartSettings = [];

    /**
     * @param \Extcode\Cart\Utility\CartUtility $cartUtility
     */
    public function injectCartUtility(
        \Extcode\Cart\Utility\CartUtility $cartUtility
    ) {
        $this->cartUtility = $cartUtility;
    }

    /**
     * @param EventRepository $eventRepository
     */
    public function injectEventRepository(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * @param \Extcode\CartEvents\Domain\Repository\CategoryRepository $categoryRepository
     */
    public function injectCategoryRepository(
        \Extcode\CartEvents\Domain\Repository\CategoryRepository $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Action initialize
     */
    protected function initializeAction()
    {
        $this->cartSettings = $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'Cart'
        );

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
        $demand = $this->createDemandObjectFromSettings('list', $this->settings);
        $demand->setActionAndClass(__METHOD__, __CLASS__);

        $events = $this->eventRepository->findDemanded($demand);

        $this->view->assign('events', $events);
        $this->view->assign('cartSettings', $this->cartSettings);

        $this->addCacheTags($events);
    }

    /**
     * action teaser
     */
    public function teaserAction()
    {
        $limit = (int)$this->settings['limit'] ?: (int)$this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'CartEvents'
        )['view']['list']['limit'];

        $events = $this->eventRepository->findByUids($limit, $this->settings['eventUids']);

        $this->view->assign('events', $events);
        $this->view->assign('cartSettings', $this->cartSettings);

        $this->addCacheTags($events);
    }

    /**
     * @param \Extcode\CartEvents\Domain\Model\Event $event
     *
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("event")
     *
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function showAction(\Extcode\CartEvents\Domain\Model\Event $event = null)
    {
        if (!$event) {
            $event = $this->getEvent();
        }
        if (!$event) {
            $this->forward('list');
        }

        $this->view->assign('event', $event);
        $this->view->assign('cartSettings', $this->cartSettings);

        $this->assignCurrencyTranslationData();

        $this->addCacheTags([$event]);
    }

    /**
     * @param \Extcode\CartEvents\Domain\Model\EventDate $eventDate
     */
    public function formAction(\Extcode\CartEvents\Domain\Model\EventDate $eventDate = null)
    {
        if (!$eventDate) {
            $arguments = $this->request->getArguments();
            foreach ($arguments as $argumentKey => $argumentValue) {
                if (preg_match('/cart-events-([a-z0-9-]+)/', $argumentKey)) {
                    $productType = $argumentValue['productType'];
                    $productUid = (int)$argumentValue['productUid'];

                    if ($productType && $productUid) {
                        $eventDateRepository = $this->objectManager->get(
                            \Extcode\CartEvents\Domain\Repository\EventDateRepository::class
                        );
                        $eventDate = $eventDateRepository->findByUid($productUid);
                    }
                }
            }
        }

        $this->view->assign('eventDate', $eventDate);
        $this->view->assign(
            'formDefinitionOverrides',
            [
                'renderingOptions' => [
                    'pageType' => $this->settings['ajaxCartEventDatesForm'],
                ],
                'renderables' => [
                    0 => [
                        'renderables' => [
                            9998 => [
                                'type' => 'Hidden',
                                'identifier' => 'productType',
                                'label' => 'productType',
                                'defaultValue' => ($eventDate ? 'CartEvents':''),
                            ],
                            9999 => [
                                'type' => 'Hidden',
                                'identifier' => 'productUid',
                                'label' => 'productUid',
                                'defaultValue' => ($eventDate ? $eventDate->getUid():''),
                            ],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * @return \Extcode\CartEvents\Domain\Model\Event
     */
    protected function getEvent()
    {
        $eventUid = 0;

        if ((int)$GLOBALS['TSFE']->page['doktype'] == 186) {
            $eventUid = (int)$GLOBALS['TSFE']->page['cart_events_event'];
        }

        if ($eventUid > 0) {
            $event =  $this->eventRepository->findByUid($eventUid);
        }

        return $event;
    }

    /**
     * Create the demand object which define which records will get shown
     *
     * @param string $type
     * @param array $settings
     *
     * @return EventDemand
     */
    protected function createDemandObjectFromSettings(string $type, array $settings) : EventDemand
    {
        /** @var EventDemand $demand */
        $demand = $this->objectManager->get(
            EventDemand::class
        );

        if ($settings['orderBy']) {
            $demand->setOrder($settings['orderBy'] . ' ' . $settings['orderDirection']);
        }

        $limit = (int)$this->settings['limit'] ?: (int)$this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'CartEvents'
        )['view'][$type]['limit'];

        $demand->setLimit($limit);

        $order = $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'CartEvents'
        )['view'][$type]['order'];

        if ($order) {
            $demand->setOrder($order);
        }

        $orderBy =  $this->settings['orderBy'] ?: $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'CartEvents'
        )['view'][$type]['orderBy'];

        $orderDirection = $this->settings['orderDirection'] ?: $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'CartEvents'
        )['view'][$type]['orderDirection'];

        if ($orderBy && $orderDirection) {
            $demand->setOrder($orderBy . ' ' . $orderDirection);
        }

        $this->addCategoriesToDemandObjectFromSettings($demand);

        return $demand;
    }

    /**
     * @param EventDemand $demand
     */
    protected function addCategoriesToDemandObjectFromSettings(EventDemand &$demand)
    {
        if ($this->settings['categoriesList']) {
            $selectedCategories = \TYPO3\CMS\Core\Utility\GeneralUtility::intExplode(
                ',',
                $this->settings['categoriesList'],
                true
            );

            $categories = [];

            if ($this->settings['listSubcategories']) {
                foreach ($selectedCategories as $selectedCategory) {
                    $category = $this->categoryRepository->findByUid($selectedCategory);
                    $categories = array_merge(
                        $categories,
                        $this->categoryRepository->findSubcategoriesRecursiveAsArray($category)
                    );
                }
            } else {
                $categories = $selectedCategories;
            }

            $demand->setCategories($categories);
        }
    }

    /**
     * assigns currency translation array to view
     */
    protected function assignCurrencyTranslationData()
    {
        if (TYPO3_MODE === 'FE') {
            $currencyTranslationData = [];

            $cartFrameworkConfig = $this->configurationManager->getConfiguration(
                \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
                'Cart'
            );

            $cart = $this->cartUtility->getCartFromSession($cartFrameworkConfig);

            if ($cart) {
                $currencyTranslationData['currencyCode'] = $cart->getCurrencyCode();
                $currencyTranslationData['currencySign'] = $cart->getCurrencySign();
                $currencyTranslationData['currencyTranslation'] = $cart->getCurrencyTranslation();
            }

            $this->view->assign('currencyTranslationData', $currencyTranslationData);
        }
    }

    /**
     * @param $events
     */
    protected function addCacheTags($events)
    {
        $cacheTags = [];

        if (!empty($GLOBALS['TSFE']) && is_object($GLOBALS['TSFE'])) {
            foreach ($events as $event) {
                $cacheTags[] = 'tx_cartevents_event_' . $event->getUid();
            }
            if (count($cacheTags) > 0) {
                $GLOBALS['TSFE']->addCacheTags($cacheTags);
            }
        }
    }
}
