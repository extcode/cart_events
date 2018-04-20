<?php

namespace Extcode\CartEvents\ViewHelpers;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

class SchemaViewHelper extends AbstractViewHelper
{
    /**
     * Output is escaped already. We must not escape children, to avoid double encoding.
     *
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments()
    {
        parent::initializeArguments();

        $this->registerArgument('event', \Extcode\CartEvents\Domain\Model\Event::class, 'event', true);
    }

    public function render()
    {
        $schemaEvents = [];

        /** @var \Extcode\CartEvents\Domain\Model\Event $event */
        $event = $this->arguments['event'];

        foreach ($event->getSlots() as $slot) {
            $schemaEvents[] = [
                '@context' => 'http://schema.org',
                '@type' => 'Event',
                'location' => [
                    '@type' => 'Place',
                    'address' => [
                        '@type' => 'Text',
                        'name' => $slot->getLocation(),
                    ],
                ],
                'name' => $event->getTitle(),
                'description' => $event->getDescription(),
                'startDate' => $this->getStartDate($slot),
                'offers' => [
                    '@type' => 'Offer',
                    'price' => $slot->getPrice(),
                    'priceCurrency' => 'EUR',
                ]
            ];
        }

        return json_encode($schemaEvents);
    }

    /**
     * @param \Extcode\CartEvents\Domain\Model\Slot $slot
     * @return string
     */
    protected function getStartDate(\Extcode\CartEvents\Domain\Model\Slot $slot) : string
    {
        $firstDate = $slot->getFirstDate();

        if ($firstDate) {
            return $firstDate->getBegin()->format(\DateTime::ATOM);
        }

        return '';
    }
}
