<?php

namespace Extcode\CartEvents\Controller;

use Extcode\CartEvents\Domain\Repository\SlotRepository;

/**
 * Cart Controller
 *
 * @author Daniel Lorenz <ext.cart@extco.de>
 */
class CartController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * Cart
     *
     * @var \Extcode\Cart\Domain\Model\Cart\Cart
     */
    protected $cart;

    /**
     * @var array
     */
    protected $cartFrameworkConfig = [];

    /**
     * Cart Utility
     *
     * @var \Extcode\Cart\Utility\CartUtility
     */
    protected $cartUtility;

    /**
     * Slot Repository
     *
     * @var SlotRepository
     */
    protected $slotRepository;

    /**
     * @param \Extcode\Cart\Utility\CartUtility $cartUtility
     */
    public function injectCartUtility(
        \Extcode\Cart\Utility\CartUtility $cartUtility
    ) {
        $this->cartUtility = $cartUtility;
    }

    /**
     * @param SlotRepository $slotRepository
     */
    public function injectDateRepository(
        SlotRepository $slotRepository
    ) {
        $this->slotRepository = $slotRepository;
    }

    /**
     * Action initialize
     */
    public function initializeAction()
    {
        parent::initializeAction();

        $this->cartFrameworkConfig = $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
            'Cart'
        );
    }

    /**
     * add Event to Cart
     */
    public function addAction()
    {
        if ($this->request->hasArgument('slot')) {
            $slotUid = $this->request->getArgument('slot');
            $slot = $this->slotRepository->findByUid($slotUid);
        }
        if ($this->request->hasArgument('event')) {
            $eventUid = $this->request->getArgument('event');
        }

        $this->cart = $this->cartUtility->getCartFromSession($this->cartFrameworkConfig);

        $topic = null;
        $date = null;

        $quantity = 0;

        if ($this->request->hasArgument('quantity')) {
            $quantity = intval($this->request->getArgument('quantity'));
        }

        if ($slot && $quantity) {
            $newProduct = $this->getProductFromSlot($slot, $quantity);

            $this->cart->addProduct($newProduct);
        }

        $this->cartUtility->writeCartToSession($this->cart, $this->cartFrameworkConfig['settings']);

        if (isset($_GET['type'])) {
            // ToDo: have different response status
            $response = [
                'status' => '200',
                'count' => $this->cart->getCount(),
                'net' => $this->cart->getNet(),
                'gross' => $this->cart->getGross(),
            ];

            return json_encode($response);
        } else {
            $this->redirect('show', 'Event', null, ['event'=> $slot->getEvent()]);
        }
    }

    /**
     * @param \Extcode\CartEvents\Domain\Model\Slot $slot
     * @param int $quantity
     *
     * @return \Extcode\Cart\Domain\Model\Cart\Product
     */
    protected function getProductFromSlot(
        \Extcode\CartEvents\Domain\Model\Slot $slot,
        int $quantity = 1
    ) {
        $event = $slot->getEvent();
        $title = implode(' - ', [$event->getTitle(), $slot->getTitle()]);
        $sku = implode(' - ', [$event->getSku(), $slot->getSku()]);

        $cartEventsFrameworkConfig = $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
            'CartEvents'
        );

        $product = new \Extcode\Cart\Domain\Model\Cart\Product(
            $cartEventsFrameworkConfig['productStorage']['class'],
            $slot->getUid(),
            $cartEventsFrameworkConfig['productStorage']['id'],
            0,
            $sku,
            $title,
            $slot->getBestSpecialPrice(),
            $this->cart->getTaxClass($event->getTaxClassId()),
            $quantity,
            true,
            null
        );

        return $product;
    }
}
