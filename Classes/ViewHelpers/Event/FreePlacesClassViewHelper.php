<?php

namespace Extcode\CartEvents\ViewHelpers\Event;

/**
 * Free Places ViewHelper
 *
 * @author Daniel Lorenz <ext.cart@extco.de>
 */
class FreePlacesClassViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    public function initializeArguments()
    {
        parent::initializeArguments();

        $this->registerArgument(
            'slot',
            \Extcode\CartEvents\Domain\Model\Slot::class,
            'slot',
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
        /** @var \Extcode\CartEvents\Domain\Model\Slot $slot */
        $slot = $this->arguments['slot'];

        $returnColorPrefix = $this->arguments['returnColorPrefix'] ?? '';

        if ($slot->isHandleSeats()) {
            $greenLowerBound  = $this->arguments['greenLowerBound']  ?? ($slot->getSeatsNumber() * 2/3);
            $yellowLowerBound = $this->arguments['yellowLowerBound'];
            $orangeLowerBound = $this->arguments['orangeLowerBound'] ?? ($slot->getSeatsNumber() * 1/3);
            $orangeLowerBound = $orangeLowerBound <= 5 ? $orangeLowerBound : 5;

            if (($yellowLowerBound > $greenLowerBound) || ($yellowLowerBound < $orangeLowerBound)) {
                unset($yellowLowerBound);
            }

            if ($slot->getSeatsAvailable() > $greenLowerBound) {
                return $returnColorPrefix . 'green';
            } elseif (isset($yellowLowerBound) && $slot->getSeatsAvailable() > $yellowLowerBound) {
                return $returnColorPrefix . 'yellow';
            } elseif ($slot->getSeatsAvailable() > $orangeLowerBound) {
                return $returnColorPrefix . 'orange';
            }
        }

        return $returnColorPrefix . 'red';
    }
}
