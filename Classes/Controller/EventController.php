<?php
declare(strict_types=1);

namespace Extcode\CartEvents\Controller;

use Extcode\CartEvents\Domain\Model\Dto\EventDemand;
use Extcode\CartEvents\Domain\Repository\EventRepository;

/**
 * Event Controller
 *
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
        $demand = $this->createDemandObjectFromSettings($this->settings);
        $demand->setActionAndClass(__METHOD__, __CLASS__);

        $events = $this->eventRepository->findDemanded($demand);

        $this->view->assign('events', $events);

        $this->addCacheTags($events);
    }

    /**
     * action teaser
     */
    public function teaserAction()
    {
        $events = $this->eventRepository->findByUids($this->settings['eventUids']);

        $this->view->assign('events', $events);

        $this->addCacheTags($events);
    }

    /**
     * @param \Extcode\CartEvents\Domain\Model\Event $event
     *
     * @ignorevalidation $event
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function showAction(\Extcode\CartEvents\Domain\Model\Event $event = null)
    {
        if (empty($event)) {
            $this->forward('list');
        }

        $this->view->assign('event', $event);
        $this->view->assign('cartSettings', $this->cartSettings);

        $this->assignCurrencyTranslationData();

        $this->addCacheTags([$event]);
    }

    /**
     * action showForm
     *
     * @param \Extcode\CartEvents\Domain\Model\Event $event
     */
    public function showFormAction(\Extcode\CartEvents\Domain\Model\Event $event = null)
    {
        if (!$event && $this->request->getPluginName()=='EventPartial') {
            $requestBuilder =$this->objectManager->get(
                \TYPO3\CMS\Extbase\Mvc\Web\RequestBuilder::class
            );
            $configurationManager = $this->objectManager->get(
                \TYPO3\CMS\Extbase\Configuration\ConfigurationManager::class
            );
            $configurationManager->setConfiguration([
                'vendorName' => 'Extcode',
                'extensionName' => 'CartEvents',
                'pluginName' => 'Events',
            ]);
            $requestBuilder->injectConfigurationManager($configurationManager);

            /**
             * @var \TYPO3\CMS\Extbase\Mvc\Web\Request $cartEventRequest
             */
            $cartEventRequest = $requestBuilder->build();

            if ($cartEventRequest->hasArgument('event')) {
                $productUid = $cartEventRequest->getArgument('event');
            }

            $eventRepository = $this->objectManager->get(
                \Extcode\CartEvents\Domain\Repository\EventRepository::class
            );

            $event = $eventRepository->findByUid($productUid);
        }
        $this->view->assign('event', $event);
        $this->view->assign('cartSettings', $this->cartSettings);

        $this->assignCurrencyTranslationData();
    }

    /**
     * Create the demand object which define which records will get shown
     *
     * @param array $settings
     *
     * @return EventDemand
     */
    protected function createDemandObjectFromSettings(array $settings) : EventDemand
    {
        /** @var EventDemand $demand */
        $demand = $this->objectManager->get(
            EventDemand::class
        );

        if ($settings['orderBy']) {
            $demand->setOrder($settings['orderBy'] . ' ' . $settings['orderDirection']);
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
        foreach ($events as $event) {
            $cacheTags[] = 'tx_cartevents_event_' . $event->getUid();
        }
        if (count($cacheTags) > 0) {
            $GLOBALS['TSFE']->addCacheTags($cacheTags);
        }
    }
}
