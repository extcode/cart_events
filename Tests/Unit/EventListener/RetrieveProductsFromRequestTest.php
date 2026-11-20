<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Tests\Unit\EventListener;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\Cart\Domain\Model\Cart\Cart;
use Extcode\Cart\Event\RetrieveProductsFromRequestEvent;
use Extcode\CartEvents\Domain\Model\Cart\ProductFactoryInterface;
use Extcode\CartEvents\EventListener\RetrieveProductsFromRequest;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Localization\Locale;
use TYPO3\CMS\Core\Localization\Locales;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversClass(RetrieveProductsFromRequest::class)]
class RetrieveProductsFromRequestTest extends UnitTestCase
{
    protected bool $resetSingletonInstances = true;

    #[Test]
    public function setsExceptionOfProductFactoryAsFlashMessage(): void
    {
        $productFactory = self::createStub(ProductFactoryInterface::class);
        $productFactory
            ->method('createProductFromRequestArguments')
            ->willThrowException(new InvalidArgumentException('Event date not found', 1741693220));

        $request = self::createStub(RequestInterface::class);
        $request->method('getArguments')->willReturn(['productType' => 'CartEvents']);

        $cart = self::createStub(Cart::class);
        $cart->method('getTaxClasses')->willReturn([]);

        $event = new RetrieveProductsFromRequestEvent($request, $cart);

        $this->mockMissingTranslation();
        (new RetrieveProductsFromRequest(self::createStub(ExtensionConfiguration::class), $productFactory))($event);

        $errors = $event->getErrors();
        self::assertCount(1, $errors);
        self::assertSame('Event date not found', $errors[0]->getMessage());
        self::assertSame(ContextualFeedbackSeverity::ERROR, $errors[0]->getSeverity());
        self::assertSame([], $event->getProducts());
    }

    /**
     * Lets LocalizationUtility::translate() return null like for a missing label.
     */
    private function mockMissingTranslation(): void
    {
        $locales = self::createStub(Locales::class);
        $locales->method('createLocaleFromRequest')->willReturn(new Locale());
        GeneralUtility::setSingletonInstance(Locales::class, $locales);

        $languageService = self::createStub(LanguageService::class);
        $languageService->method('translate')->willReturn(null);
        $languageServiceFactory = self::createStub(LanguageServiceFactory::class);
        $languageServiceFactory->method('create')->willReturn($languageService);
        GeneralUtility::addInstance(LanguageServiceFactory::class, $languageServiceFactory);
    }
}
