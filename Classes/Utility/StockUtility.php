<?php

namespace Extcode\CartEvents\Utility;

/**
 * Stock Utility
 *
 * @author Daniel Lorenz <ext.cart@extco.de>
 */
class StockUtility
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * MailHandler constructor
     */
    public function __construct()
    {
        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Extbase\Object\ObjectManager::class
        );

        $this->logManager = $this->objectManager->get(
            \TYPO3\CMS\Core\Log\LogManager::class
        );

        $this->persistenceManager = $this->objectManager->get(
            \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class
        );

        $this->configurationManager = $this->objectManager->get(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManager::class
        );

        $this->config = $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
            'CartEvents'
        );
    }

    public function handleStock($params)
    {
        $cartProduct = $params['cartProduct'];

        if ($cartProduct->getProductType() == $this->config['productStorage']['class']) {
            $repository = $this->objectManager->get(
                $this->config['productStorage']['class']
            );

            $product = $repository->findByUid($cartProduct->getProductId());

            if ($product && $product->isHandleSeats()) {
                $product->setSeatsTaken($product->getSeatsTaken() + $cartProduct->getQuantity());
            }

            $repository->update($product);

            $this->persistenceManager->persistAll();
        }
    }
}
