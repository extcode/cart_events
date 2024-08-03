<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Controller;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\Cart\Domain\Model\Cart\Cart;
use Extcode\Cart\Service\SessionHandler;
use Extcode\Cart\Utility\CartUtility;
use Extcode\CartEvents\Domain\Model\Dto\EventDemand;
use Extcode\CartEvents\Domain\Model\Event;
use Extcode\CartEvents\Domain\Model\EventDate;
use Extcode\CartEvents\Domain\Model\PriceCategory;
use Extcode\CartEvents\Domain\Repository\CategoryRepository;
use Extcode\CartEvents\Domain\Repository\EventDateRepository;
use Extcode\CartEvents\Domain\Repository\EventRepository;
use Extcode\CartEvents\Domain\Repository\PriceCategoryRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation\IgnoreValidation;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Form\Mvc\Persistence\FormPersistenceManagerInterface;

class EventController extends ActionController
{
    private Cart $cart;

    protected array $cartConfiguration = [];

    public function __construct(
        private readonly SessionHandler $sessionHandler,
        private readonly CartUtility $cartUtility,
        private readonly EventRepository $eventRepository,
        private readonly CategoryRepository $categoryRepository,
    ) {}

    protected function initializeAction(): void
    {
        $this->cartConfiguration = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
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

    public function listAction(): ResponseInterface
    {
        if (!$this->settings) {
            $this->settings = [];
        }
        $demand = $this->createDemandObjectFromSettings('list', $this->settings);
        $demand->setActionAndClass(__METHOD__, self::class);

        $events = $this->eventRepository->findDemanded($demand);

        $this->view->assign('events', $events);
        $this->view->assign('cartSettings', $this->cartConfiguration);

        $this->addCacheTags($events);
        return $this->htmlResponse();
    }

    public function teaserAction(): ResponseInterface
    {
        $limit = (int)$this->settings['limit'] ?: (int)$this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'CartEvents'
        )['view']['list']['limit'];

        $events = $this->eventRepository->findByUids($limit, $this->settings['eventUids']);

        $this->view->assign('events', $events);
        $this->view->assign('cartSettings', $this->cartConfiguration);

        $this->addCacheTags($events);
        return $this->htmlResponse();
    }

    /**
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("event")
     */
    #[IgnoreValidation(['value' => 'event'])]
    public function showAction(Event $event = null): ResponseInterface
    {
        if (!$event) {
            $event = $this->getEvent();
        }
        if (!$event) {
            return new ForwardResponse('list');
        }

        $this->view->assign('event', $event);
        $this->view->assign('cartSettings', $this->cartConfiguration);

        $this->assignCurrencyTranslationData();

        $this->addCacheTags([$event]);
        return $this->htmlResponse();
    }

    #[IgnoreValidation(['value' => 'eventDate'])]
    #[IgnoreValidation(['value' => 'priceCategory'])]
    public function formAction(EventDate $eventDate = null, PriceCategory $priceCategory = null): ResponseInterface
    {
        if (!$eventDate) {
            $arguments = $this->request->getArguments();
            foreach ($arguments as $argumentKey => $argumentValue) {
                if (is_array($argumentValue) && array_key_exists('productType', $argumentValue) && $argumentValue['productType'] === 'CartEvents') {
                    $eventDateId = (int)$argumentValue['eventDateId'];
                    $priceCategoryId = (int)$argumentValue['priceCategoryId'];

                    if ($eventDateId) {
                        $eventDateRepository = GeneralUtility::makeInstance(
                            EventDateRepository::class
                        );
                        $eventDate = $eventDateRepository->findByUid($eventDateId);

                        $formDefinition = $eventDate->getEvent()->getFormDefinition();
                        $formPersistenceManager = GeneralUtility::makeInstance(
                            FormPersistenceManagerInterface::class
                        );
                        $form = $formPersistenceManager->load($formDefinition);

                        if ($form['identifier'] !== $argumentKey) {
                            throw new \InvalidArgumentException();
                        }

                        if ($priceCategoryId) {
                            $priceCategoryRepository = GeneralUtility::makeInstance(
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
        return $this->htmlResponse();
    }

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
     */
    protected function createDemandObjectFromSettings(string $type, array $settings): EventDemand
    {
        /** @var EventDemand $demand */
        $demand = GeneralUtility::makeInstance(
            EventDemand::class
        );

        if (isset($settings['view']) &&
            is_array($settings['view']) &&
            isset($settings['view'][$type]) &&
            is_array($settings['view'][$type])
        ) {
            // Use default TypoScript settings for plugin configuration
            $limit = (int)$settings['view'][$type]['limit'];
            $orderBy = $settings['view'][$type]['orderBy'];
            $orderDirection = $settings['view'][$type]['orderDirection'];
        }

        if (isset($settings['limit']) && (int)$settings['limit'] > 0) {
            $limit = (int)$settings['limit'];
        }
        if (isset($limit) && $limit > 0) {
            $demand->setLimit($limit);
        }

        if (isset($settings['orderBy']) && !empty($settings['orderBy'])) {
            $orderBy = $settings['orderBy'];
        }
        if (isset($settings['orderDirection']) && !empty($settings['orderDirection'])) {
            $orderDirection = $settings['orderDirection'];
        }
        if (isset($orderBy) && isset($orderDirection)) {
            $demand->setOrder($orderBy . ' ' . $orderDirection);
        }

        $this->addCategoriesToDemandObjectFromSettings($demand);

        return $demand;
    }

    protected function addCategoriesToDemandObjectFromSettings(EventDemand &$demand): void
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
    protected function assignCurrencyTranslationData(): void
    {
        $this->restoreSession();

        $currencyTranslationData = [
            'currencyCode' => $this->cart->getCurrencyCode(),
            'currencySign' => $this->cart->getCurrencySign(),
            'currencyTranslation' => $this->cart->getCurrencyTranslation(),
        ];

        $this->view->assign('currencyTranslationData', $currencyTranslationData);
    }

    protected function addCacheTags(iterable $events): void
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

    protected function restoreSession(): void
    {
        $cart = $this->sessionHandler->restoreCart($this->cartConfiguration['settings']['cart']['pid']);

        if ($cart instanceof Cart) {
            $this->cart = $cart;
            return;
        }

        $this->cart = $this->cartUtility->getNewCart($this->cartConfiguration);
        $this->sessionHandler->writeCart($this->cartConfiguration['settings']['cart']['pid'], $this->cart);
    }
}
