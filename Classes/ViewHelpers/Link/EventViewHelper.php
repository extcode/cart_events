<?php

namespace Extcode\CartEvents\ViewHelpers\Link;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class EventViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Link\ActionViewHelper
{
    public function initializeArguments()
    {
        parent::initializeArguments();

        $this->registerArgument('event', \Extcode\CartEvents\Domain\Model\Event::class, 'event', true);
        $this->registerArgument('settings', 'array', 'settings array', true);
    }

    /**
     * @return string Rendered link
     */
    public function render()
    {
        $event = $this->arguments['event'];

        $page = $this->getEventPage($event);

        if ($page) {
            $this->arguments['pageUid'] = $page['uid'];
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
     * @param \Extcode\CartEvents\Domain\Model\Event $event
     * @return array|bool
     */
    protected function getEventPage(\Extcode\CartEvents\Domain\Model\Event $event)
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
}
