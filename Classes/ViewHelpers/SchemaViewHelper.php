<?php

namespace Extcode\CartEvents\ViewHelpers;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\CartEvents\Domain\Model\Event;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class SchemaViewHelper extends AbstractViewHelper
{
    /**
     * Output is escaped already. We must not escape children, to avoid double encoding.
     *
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument(
            'event',
            Event::class,
            'event',
            true
        );
    }

    public function render(): string
    {
        $schemaEvents = [];

        $event = $this->arguments['event'];

        if ($event->getEventDates()) {
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
                    ],
                ];
            }
        }

        return json_encode($schemaEvents);
    }
}
