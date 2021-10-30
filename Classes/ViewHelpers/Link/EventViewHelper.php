<?php

namespace Extcode\CartEvents\ViewHelpers\Link;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\CartEvents\Domain\Model\Event;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\LanguageAspect;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\ViewHelpers\Link\ActionViewHelper;

class EventViewHelper extends ActionViewHelper
{
    public function initializeArguments()
    {
        parent::initializeArguments();

        $this->registerArgument(
            'event',
            Event::class,
            'event',
            true
        );
        $this->registerArgument(
            'settings',
            'array',
            'settings array',
            true
        );
    }

    /**
     * returns the gendered link
     */
    public function render(): string
    {
        $event = $this->arguments['event'];

        $page = $this->getEventPage($event);

        if ($page) {
            $languageId = $this->getCurrentLanguageAspect()->getId();

            if (
                !(
                    GeneralUtility::hideIfDefaultLanguage($page['l18n_cfg'])
                    && (!$languageId || ($languageId && !$page['_PAGES_OVERLAY']))
                )
                &&
                !(
                    $languageId
                    && GeneralUtility::hideIfNotTranslated($page['l18n_cfg'])
                    && !$page['_PAGES_OVERLAY']
                )
            ) {
                $this->arguments['pageUid'] = $page['uid'];
            } else {
                return '';
            }
        } else {
            if ($event->getCategory() && $event->getCategory()->getCartEventShowPid()) {
                $this->arguments['pageUid'] = $event->getCategory()->getCartEventShowPid();
            } elseif ($this->arguments['settings']['showPageUids']) {
                $this->arguments['pageUid'] = $this->arguments['settings']['showPageUids'];
            }

            $this->arguments['action'] = 'show';
            $this->arguments['arguments']['event'] = $event;
        }

        return parent::render();
    }

    /**
     * @param Event $event
     * @return array|bool
     */
    protected function getEventPage(Event $event)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
        return $queryBuilder->select('*')
            ->from('pages')
            ->where(
                $queryBuilder->expr()->eq('cart_events_event', $event->getUid())
            )
            ->orderBy('sorting')
            ->setMaxResults(1)
            ->execute()
            ->fetch();
    }

    /**
     * @return LanguageAspect
     * @throws \TYPO3\CMS\Core\Context\Exception\AspectNotFoundException
     */
    protected function getCurrentLanguageAspect(): LanguageAspect
    {
        return GeneralUtility::makeInstance(Context::class)->getAspect('language');
    }
}
