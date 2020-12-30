<?php
declare(strict_types=1);
namespace Extcode\CartEvents\Controller;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\Cart\Utility\CartUtility;
use Extcode\CartEvents\Domain\Model\Dto\EventDemand;
use Extcode\CartEvents\Domain\Model\Event;
use Extcode\CartEvents\Domain\Model\EventDate;
use Extcode\CartEvents\Domain\Model\PriceCategory;
use Extcode\CartEvents\Domain\Repository\CategoryRepository;
use Extcode\CartEvents\Domain\Repository\EventDateRepository;
use Extcode\CartEvents\Domain\Repository\EventRepository;
use Extcode\CartEvents\Domain\Repository\PriceCategoryRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Form\Mvc\Persistence\FormPersistenceManager;

class EventController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @var CartUtility
     */
    protected $cartUtility;

    /**
     * @var EventRepository
     */
    protected $eventRepository;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var array
     */
    protected $cartSettings = [];

    /**
     * @param CartUtility $cartUtility
     */
    public function injectCartUtility(CartUtility $cartUtility)
    {
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
     * @param CategoryRepository $categoryRepository
     */
    public function injectCategoryRepository(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Action initialize
     */
    protected function initializeAction()
    {
        $this->cartSettings = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
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

    public function listAction(): void
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

    public function teaserAction(): void
    {
        $limit = (int)$this->settings['limit'] ?: (int)$this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'CartEvents'
        )['view']['list']['limit'];

        $events = $this->eventRepository->findByUids($limit, $this->settings['eventUids']);

        $this->view->assign('events', $events);
        $this->view->assign('cartSettings', $this->cartSettings);

        $this->addCacheTags($events);
    }

    /**
     * @param Event $event
     *
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("event")
     *
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function showAction(Event $event = null): void
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
     * @param EventDate $eventDate
     * @param PriceCategory $priceCategory
     */
    public function formAction(EventDate $eventDate = null, PriceCategory $priceCategory = null)
    {
        if (!$eventDate) {
            $arguments = $this->request->getArguments();
            foreach ($arguments as $argumentKey => $argumentValue) {
                if (is_array($argumentValue) && array_key_exists('productType', $argumentValue) && $argumentValue['productType'] === 'CartEvents') {
                    $eventDateId = (int)$argumentValue['eventDateId'];
                    $priceCategoryId = (int)$argumentValue['priceCategoryId'];

                    if ($eventDateId) {
                        $eventDateRepository = $this->objectManager->get(
                            EventDateRepository::class
                        );
                        $eventDate = $eventDateRepository->findByUid($eventDateId);

                        $formDefinition = $eventDate->getEvent()->getFormDefinition();
                        $formPersistenceManager = $this->objectManager->get(
                            FormPersistenceManager::class
                        );
                        $form = $formPersistenceManager->load($formDefinition);

                        if ($form['identifier'] !== $argumentKey) {
                            throw new \InvalidArgumentException();
                        }

                        if ($priceCategoryId) {
                            $priceCategoryRepository = $this->objectManager->get(
                                PriceCategoryRepository::class
                            );
                            $priceCategory = $priceCategoryRepository->findByUid($priceCategoryId);
                        }
                    }
                }
            }
        }

        if (!$eventDate) {
            throw new \InvalidArgumentException();
        }

        $this->view->assign('eventDate', $eventDate);

        $formDefinitionOverrides = [
            'renderingOptions' => [
                'pageType' => $this->settings['ajaxCartEventDatesForm'],
            ],
            'renderables' => [
                0 => [
                    'renderables' => [
                        9997 => [
                            'type' => 'Hidden',
                            'identifier' => 'productType',
                            'label' => 'productType',
                            'defaultValue' => ($eventDate ? 'CartEvents' : ''),
                        ],
                        9998 => [
                            'type' => 'Hidden',
                            'identifier' => 'eventDateId',
                            'label' => 'eventDateId',
                            'defaultValue' => ($eventDate ? $eventDate->getUid() : ''),
                        ],
                        9999 => [
                            'type' => 'Hidden',
                            'identifier' => 'priceCategoryId',
                            'label' => 'priceCategoryId',
                            'defaultValue' => ($priceCategory ? $priceCategory->getUid() : ''),
                        ],
                    ],
                ],
            ],
        ];

        $this->view->assign(
            'formDefinitionOverrides',
            $formDefinitionOverrides
        );
    }

    /**
     * @return Event|null
     */
    protected function getEvent(): ?Event
    {
        $eventUid = 0;

        if ((int)$GLOBALS['TSFE']->page['doktype'] == 186) {
            $eventUid = (int)$GLOBALS['TSFE']->page['cart_events_event'];
        }

        if ($eventUid > 0) {
            $event =  $this->eventRepository->findByUid($eventUid);
            if ($event && $event instanceof Event) {
                return $event;
            }
        }

        return null;
    }

    /**
     * Create the demand object which define which records will get shown
     *
     * @param string $type
     * @param array $settings
     *
     * @return EventDemand
     */
    protected function createDemandObjectFromSettings(string $type, array $settings): EventDemand
    {
        /** @var EventDemand $demand */
        $demand = $this->objectManager->get(
            EventDemand::class
        );

        if ($settings['orderBy']) {
            $demand->setOrder($settings['orderBy'] . ' ' . $settings['orderDirection']);
        }

        $limit = (int)$this->settings['limit'] ?: (int)$this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'CartEvents'
        )['view'][$type]['limit'];

        $demand->setLimit($limit);

        $order = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'CartEvents'
        )['view'][$type]['order'];

        if ($order) {
            $demand->setOrder($order);
        }

        $orderBy =  $this->settings['orderBy'] ?: $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'CartEvents'
        )['view'][$type]['orderBy'];

        $orderDirection = $this->settings['orderDirection'] ?: $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
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
            $selectedCategories = GeneralUtility::intExplode(
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
                ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
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
