<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Domain\Finisher\Form;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\Cart\Domain\Finisher\Form\AddToCartFinisherInterface;
use Extcode\Cart\Domain\Model\Cart\Cart;
use Extcode\Cart\Domain\Model\Cart\FeVariant;
use Extcode\CartEvents\Domain\Model\Cart\ProductFactoryInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AddToCartFinisher implements AddToCartFinisherInterface
{
    protected Cart $cart;

    public function __construct(
        private readonly ProductFactoryInterface $productFactory,
    ) {}

    public function getProductFromForm(
        array $formValues,
        Cart $cart
    ): array {
        $errors = [];

        if ($formValues['productType'] !== 'CartEvents') {
            return [$errors, []];
        }
        unset($formValues['productType']);

        $requestArguments = [
            'eventDate' => $formValues['eventDateId'],
            'priceCategory' => $formValues['priceCategoryId'],
            'quantity' => $formValues['quantity'] ?? 1,
        ];
        unset($formValues['eventDateId']);
        unset($formValues['priceCategoryId']);
        unset($formValues['quantity']);

        if (!empty($formValues)) {
            $requestArguments['feVariant'] = $this->getFeVariant($formValues);
        }
        $newProduct = $this->productFactory->createProductFromRequestArguments(
            $requestArguments,
            $cart->getTaxClasses(),
            (bool)GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('cart_events', 'inputIsNetPrice')
        );

        $newProduct->setMaxNumberInCart(1);
        $newProduct->setMinNumberInCart(1);

        return [$errors, [$newProduct]];
    }

    protected function getFeVariant(array $data): ?FeVariant
    {
        $feVariant = null;

        if (!empty($data)) {
            $feVariants = [];
            foreach ($data as $dataKey => $dataValue) {
                if (!empty($dataKey) && !empty($dataValue)) {
                    $feVariants[] = [
                        'sku' => $dataKey,
                        'title' => $dataKey,
                        'value' => $dataValue,
                    ];
                }
            }

            $feVariant = GeneralUtility::makeInstance(
                FeVariant::class,
                $feVariants
            );
        }

        return $feVariant;
    }
}
