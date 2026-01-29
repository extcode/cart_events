<?php

namespace Extcode\CartEvents\ViewHelpers\Form;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */
use Extcode\Cart\ViewHelpers\Format\CurrencyViewHelper;
use Extcode\CartEvents\Domain\Model\EventDate;
use Extcode\CartEvents\Domain\Model\PriceCategory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class PriceCategorySelectViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    /**
     * @var EventDate
     */
    protected $eventDate;

    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument(
            'eventDate',
            EventDate::class,
            'event date for select options',
            true
        );
        $this->registerArgument('id', 'string', 'id for select');
        $this->registerArgument('class', 'string', 'class for select');
        $this->registerArgument('name', 'string', 'name for select');
        $this->registerArgument('blank', 'string', 'blank adds blank option');
        $this->registerArgument('required', 'bool', 'required adds html5 required', false, true);
    }

    public function render(): string
    {
        $this->eventDate = $this->arguments['eventDate'];

        $select = [];

        if ($this->hasArgument('id')) {
            $select[] = 'id="' . $this->arguments['id'] . '" ';
        }
        if ($this->hasArgument('class')) {
            $select[] = 'class="' . $this->arguments['class'] . '" ';
        }
        if ($this->hasArgument('name')) {
            $select[] = 'name="' . $this->arguments['name'] . '" ';
        }
        if ($this->hasArgument('required')) {
            $select[] = 'required ';
        }

        $out = '<select ' . implode(' ', $select) . '>';

        if ($this->hasArgument('blank')) {
            $out .= '<option value="">' . $this->arguments['blank'] . '</option>';
        }

        $options = $this->getOptions();

        foreach ($options as $option) {
            $out .= $option;
        }

        $out .= '</select>';

        return $out;
    }

    protected function getOptions(): array
    {
        $options = [];

        $currencyViewHelper = GeneralUtility::makeInstance(
            CurrencyViewHelper::class
        );
        $currencyViewHelper->initialize();
        $currencyViewHelper->setRenderingContext($this->renderingContext);

        foreach ($this->eventDate->getPriceCategories() as $priceCategory) {
            /**
             * @var PriceCategory $priceCategory
             */
            $currencyViewHelper->setRenderChildrenClosure(
                fn() => $priceCategory->getPrice()
            );
            $regularPrice = $currencyViewHelper->render();

            $value = 'value="' . $priceCategory->getUid() . '"';
            $data = 'data-regular-price="' . $regularPrice . '"';

            $specialPrice = $priceCategory->getBestSpecialPrice();
            if ($specialPrice) {
                $currencyViewHelper->setRenderChildrenClosure(
                    fn() => $priceCategory->getBestPrice()
                );
                $specialPricePrice = $currencyViewHelper->render();

                $specialPricePercentageDiscount = number_format($priceCategory->getBestSpecialPricePercentageDiscount(), 2);

                $data .= ' data-title="' . $specialPrice->getTitle() . '"';
                $data .= ' data-special-price="' . $specialPricePrice . '"';
                $data .= ' data-discount="' . $specialPricePercentageDiscount . '"';
            }

            $disabled = '';
            if (!$priceCategory->isAvailable()
                && $priceCategory->getEventDate()->isHandleSeatsInPriceCategory()
            ) {
                $disabled = 'disabled';
            }

            $option = '<option ' . $value . ' ' . $data . ' ' . $disabled . '>' . $priceCategory->getTitle() . '</option>';
            $options[$priceCategory->getSku()] = $option;
        }

        return $options;
    }
}
