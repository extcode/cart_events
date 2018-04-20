<?php
declare(strict_types=1);

namespace Extcode\CartEvents\Hooks;

/**
 * CheckAvailability Hook
 *
 * @author Daniel Lorenz <ext.cart@extco.de>
 */
class CheckAvailabilityHook implements \Extcode\Cart\Hooks\CheckAvailabilityHookInterface
{
    /**
     * Object Manager
     *
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager;

    /**
     * Slot Repository
     *
     * @var \Extcode\CartEvents\Domain\Repository\SlotRepository
     */
    protected $slotRepository;

    /**
     * @param array $params
     * @return bool
     */
    public function checkAvailability(array $params) : bool
    {
        $cartProduct = $params['cartProduct'];
        $quantity = (int)$params['quantity'];

        $this->objectManager = new \TYPO3\CMS\Extbase\Object\ObjectManager();

        $this->configurationManager = $this->objectManager->get(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::class
        );

        $frameworkConfig = $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
            'CartEvents'
        );

        if ($frameworkConfig['productStorage']['class'] != $cartProduct->getProductType()) {
            return true;
        }

        $this->objectManager = new \TYPO3\CMS\Extbase\Object\ObjectManager();

        $this->slotRepository = $this->objectManager->get(
            \Extcode\CartEvents\Domain\Repository\SlotRepository::class
        );

        $querySettings = $this->slotRepository->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $this->slotRepository->setDefaultQuerySettings($querySettings);

        $slot = $this->slotRepository->findByIdentifier($cartProduct->getProductId());

        if (!$slot->isHandleSeats()) {
            return true;
        }

        if ($quantity <= $slot->getSeatsAvailable()) {
            return true;
        }

        return false;
    }
}
