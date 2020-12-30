<?php

namespace Extcode\CartEvents\Hooks;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook into tcemain which is used to show preview of news item
 */
class DataHandler
{

    /**
     * Flushes the cache if a news record was edited.
     * This happens on two levels: by UID and by PID.
     *
     * @param array $params
     */
    public function clearCachePostProc(array $params)
    {
        if (isset($params['table']) && ($params['table'] === 'tx_cartevents_domain_model_event')) {
            $cacheTagsToFlush = [];
            if (isset($params['uid'])) {
                $cacheTagsToFlush[] = 'tx_cartevents_event_' . $params['uid'];
            }
            if (isset($params['uid_page'])) {
                $cacheTagsToFlush[] = 'tx_cartevents_event_' . $params['uid_page'];
            }

            $cacheManager = GeneralUtility::makeInstance(CacheManager::class);
            foreach ($cacheTagsToFlush as $cacheTag) {
                $cacheManager->flushCachesInGroupByTag('pages', $cacheTag);
            }
        }
    }
}
