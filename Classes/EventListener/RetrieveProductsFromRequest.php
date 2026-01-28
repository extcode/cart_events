<?php

declare(strict_types=1);

namespace Extcode\CartEvents\EventListener;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Exception;
use Extcode\Cart\Event\RetrieveProductsFromRequestEvent;
use Extcode\CartEvents\Domain\Model\Cart\ProductFactoryInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class RetrieveProductsFromRequest
{
    public function __construct(
        private readonly ExtensionConfiguration $extensionConfiguration,
        private readonly ProductFactoryInterface $productFactory,
    ) {}

    public function __invoke(RetrieveProductsFromRequestEvent $event): void
    {
        $requestArguments = $event->getRequest()->getArguments();

        if ($requestArguments['productType'] !== 'CartEvents') {
            return;
        }

        try {
            $product = $this->productFactory->createProductFromRequestArguments(
                $requestArguments,
                $event->getCart()->getTaxClasses(),
                (bool)$this->extensionConfiguration->get('cart_events', 'inputIsNetPrice'),
            );

            $event->addProduct($product);
        } catch (Exception $exception) {
            $event->setErrors(
                [
                    'messageBody' => LocalizationUtility::translate(
                        $exception->getCode(),
                        'CartEvents'
                    ) ?? $exception->getMessage(),
                    'severity' => ContextualFeedbackSeverity::ERROR,
                ]
            );
            return;
        }
    }
}
