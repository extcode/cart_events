<?php

namespace Extcode\CartEvents\ViewHelpers\Event;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\CartEvents\Domain\Model\EventDate;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class FreePlacesClassViewHelper extends AbstractViewHelper
{
    public function initializeArguments()
    {
        parent::initializeArguments();

        $this->registerArgument(
            'eventDate',
            EventDate::class,
            'eventDate',
            true
        );

        $this->registerArgument(
            'greenLowerBound',
            'int',
            'greenLowerBound',
            false
        );

        $this->registerArgument(
            'yellowLowerBound',
            'int',
            'yellowLowerBound',
            false
        );

        $this->registerArgument(
            'orangeLowerBound',
            'int',
            'orangeLowerBound',
            false
        );

        $this->registerArgument(
            'returnColorPrefix',
            'string',
            'prefix added to return value',
            false
        );
    }

    /**
     * @return string
     */
    public function render()
    {
        $eventDate = $this->arguments['eventDate'];

        $returnColorPrefix = $this->arguments['returnColorPrefix'] ?? '';

        if ($eventDate->isHandleSeats()) {
            $greenLowerBound  = $this->arguments['greenLowerBound']  ?? ($eventDate->getSeatsNumber() * 2/3);
            $yellowLowerBound = $this->arguments['yellowLowerBound'];
            $orangeLowerBound = $this->arguments['orangeLowerBound'] ?? ($eventDate->getSeatsNumber() * 1/3);
            $orangeLowerBound = $orangeLowerBound <= 5 ? $orangeLowerBound : 5;

            if (($yellowLowerBound > $greenLowerBound) || ($yellowLowerBound < $orangeLowerBound)) {
                unset($yellowLowerBound);
            }

            if ($eventDate->getSeatsAvailable() > $greenLowerBound) {
                return $returnColorPrefix . 'green';
            } elseif (isset($yellowLowerBound) && $eventDate->getSeatsAvailable() > $yellowLowerBound) {
                return $returnColorPrefix . 'yellow';
            } elseif ($eventDate->getSeatsAvailable() > $orangeLowerBound) {
                return $returnColorPrefix . 'orange';
            }
        }

        return $returnColorPrefix . 'red';
    }
}
