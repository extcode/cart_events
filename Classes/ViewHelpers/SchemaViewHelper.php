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

        foreach ($event->getEventDates() as $eventDate) {
            $schemaEvents[] = [
                '@context' => 'http://schema.org',
                '@type' => 'Event',
                'location' => [
                    '@type' => 'Place',
                    'address' => [
                        '@type' => 'Text',
                        'name' => $eventDate->getLocation(),
                    ],
                ],
                'name' => $event->getTitle(),
                'description' => $event->getDescription(),
                'startDate' => $eventDate->getBegin()->format(\DateTime::ATOM),
                'offers' => [
                    '@type' => 'Offer',
                    'price' => $eventDate->getPrice(),
                    'priceCurrency' => 'EUR',
                ]
            ];
        }

        return json_encode($schemaEvents);
    }
}
