<?php

declare(strict_types=1);

namespace Extcode\CartEvents\EventListener\Form;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Exception;
use Extcode\Cart\Domain\Model\Cart\FeVariant;
use Extcode\Cart\Event\Form\AddToCartFinisherEvent;
use Extcode\CartEvents\Domain\Model\Cart\ProductFactoryInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

final readonly class AddToCartFinisher
{
    public function __construct(
        private ExtensionConfiguration $extensionConfiguration,
        private ProductFactoryInterface $productFactory,
    ) {}

    public function __invoke(AddToCartFinisherEvent $event): void
    {
        $formValues = $event->getFormValues();

        if (($formValues['productType'] ?? '') !== 'CartEvents') {
            return;
        }

        $requestArguments = [
            'eventDate' => $formValues['eventDateId'] ?? null,
            'quantity' => $formValues['quantity'] ?? 1,
        ];
        if (!empty($formValues['priceCategoryId'])) {
            $requestArguments['priceCategory'] = (int)$formValues['priceCategoryId'];
        }

        unset(
            $formValues['productType'],
            $formValues['eventDateId'],
            $formValues['priceCategoryId'],
            $formValues['quantity'],
        );

        $feVariant = $this->getFeVariant($formValues);
        if ($feVariant instanceof FeVariant) {
            $requestArguments['feVariant'] = $feVariant;
        }

        try {
            $product = $this->productFactory->createProductFromRequestArguments(
                $requestArguments,
                $event->getCart()->getTaxClasses(),
                (bool)$this->extensionConfiguration->get('cart_events', 'inputIsNetPrice'),
            );
        } catch (Exception $exception) {
            $event->setErrors(
                [
                    'messageBody' => LocalizationUtility::translate(
                        (string)$exception->getCode(),
                        'CartEvents'
                    ) ?? $exception->getMessage(),
                    'severity' => ContextualFeedbackSeverity::ERROR,
                ]
            );
            return;
        }

        $product->setMaxNumberInCart(1);
        $product->setMinNumberInCart(1);

        $event->setCartProducts([$product]);
    }

    private function getFeVariant(array $formValues): ?FeVariant
    {
        $feVariants = [];
        foreach ($formValues as $key => $value) {
            if (!empty($key) && !empty($value)) {
                $feVariants[] = [
                    'sku' => $key,
                    'title' => $key,
                    'value' => $value,
                ];
            }
        }

        if ($feVariants === []) {
            return null;
        }

        return new FeVariant($feVariants);
    }
}
