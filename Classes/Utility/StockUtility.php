<?php

namespace Extcode\CartEvents\Utility;

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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

    /**
     * @param array $params
     */
    public function handleStock(array $params): void
    {
        $cartProduct = $params['cartProduct'];

        if ($cartProduct->getProductType() === 'CartEvents') {
            $eventDateRepository = $this->objectManager->get(
                \Extcode\CartEvents\Domain\Repository\EventDateRepository::class
            );
            $priceCategoryRepository = $this->objectManager->get(
                \Extcode\CartEvents\Domain\Repository\PriceCategoryRepository::class
            );

            /** @var \Extcode\CartEvents\Domain\Model\EventDate $eventDate */
            $eventDate = $eventDateRepository->findByUid($cartProduct->getProductId());

            if ($eventDate && $eventDate->isHandleSeats()) {
                if ($eventDate->isHandleSeatsInPriceCategory()) {
                    /** @var \Extcode\Cart\Domain\Model\Cart\BeVariant $cartBeVariant */
                    foreach ($cartProduct->getBeVariants() as $cartBeVariant) {
                        $id = (int) end(explode('-', $cartBeVariant->getId()));
                        /** @var \Extcode\CartEvents\Domain\Model\PriceCategory $priceCategory */
                        $priceCategory = $priceCategoryRepository->findByUid($id);
                        $priceCategory->setSeatsTaken($priceCategory->getSeatsTaken() + $cartBeVariant->getQuantity());
                        $priceCategoryRepository->update($priceCategory);
                    }

                    $this->persistenceManager->persistAll();

                    $this->flushCache($eventDate->getEvent()->getUid());

                    return;
                }

                $eventDate->setSeatsTaken($eventDate->getSeatsTaken() + $cartProduct->getQuantity());
                $eventDateRepository->update($eventDate);

                $this->persistenceManager->persistAll();

                $this->flushCache($eventDate->getEvent()->getUid());
            }
        }
    }

    /**
     * @param int $cartProductId
     */
    protected function flushCache(int $cartProductId)
    {
        $cacheTag = 'tx_cartevents_event_' . $cartProductId;

        $cacheManager = GeneralUtility::makeInstance(CacheManager::class);

        $cacheManager->flushCachesInGroupByTag('pages', $cacheTag);
    }
}
