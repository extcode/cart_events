<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Tests\Unit\EventListener\Form;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\Cart\Domain\Model\Cart\Cart;
use Extcode\Cart\Domain\Model\Cart\FeVariant;
use Extcode\Cart\Domain\Model\Cart\Product;
use Extcode\Cart\Domain\Model\Cart\TaxClass;
use Extcode\Cart\Event\Form\AddToCartFinisherEvent;
use Extcode\CartEvents\Domain\Model\Cart\ProductFactoryInterface;
use Extcode\CartEvents\EventListener\Form\AddToCartFinisher;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversClass(AddToCartFinisher::class)]
class AddToCartFinisherTest extends UnitTestCase
{
    private ProductFactoryInterface&MockObject $productFactory;

    private Cart $cart;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productFactory = $this->createMock(ProductFactoryInterface::class);

        $this->cart = self::createStub(Cart::class);
        $this->cart->method('getTaxClasses')->willReturn([]);
    }

    #[Test]
    public function ignoresFormsOfOtherProductTypes(): void
    {
        $this->productFactory->expects(self::never())->method('createProductFromRequestArguments');

        $event = new AddToCartFinisherEvent(['productType' => 'CartProducts'], $this->cart);
        $this->createSubject()($event);

        self::assertSame([], $event->getCartProducts());
        self::assertSame([], $event->getErrors());
    }

    #[Test]
    public function addsProductCreatedFromFormValues(): void
    {
        $product = $this->createProduct();

        $this->productFactory
            ->expects(self::once())
            ->method('createProductFromRequestArguments')
            ->with(
                [
                    'eventDate' => '3',
                    'quantity' => 1,
                    'priceCategory' => 5,
                ],
                [],
                true
            )
            ->willReturn($product);

        $event = new AddToCartFinisherEvent(
            [
                'productType' => 'CartEvents',
                'eventDateId' => '3',
                'priceCategoryId' => '5',
            ],
            $this->cart
        );
        $this->createSubject()($event);

        self::assertSame([$product], $event->getCartProducts());
        self::assertSame(1, $product->getMaxNumberInCart());
        self::assertSame(1, $product->getMinNumberInCart());
        self::assertSame([], $event->getErrors());
    }

    #[Test]
    public function passesNoPriceCategoryForEmptyPriceCategoryId(): void
    {
        $this->productFactory
            ->expects(self::once())
            ->method('createProductFromRequestArguments')
            ->with(
                [
                    'eventDate' => '3',
                    'quantity' => '2',
                ]
            )
            ->willReturn($this->createProduct());

        $event = new AddToCartFinisherEvent(
            [
                'productType' => 'CartEvents',
                'eventDateId' => '3',
                'priceCategoryId' => '',
                'quantity' => '2',
            ],
            $this->cart
        );
        $this->createSubject()($event);
    }

    #[Test]
    public function passesAdditionalNonEmptyFormValuesAsFrontendVariant(): void
    {
        $this->productFactory
            ->expects(self::once())
            ->method('createProductFromRequestArguments')
            ->with(
                self::callback(
                    static fn(array $requestArguments): bool => $requestArguments['feVariant'] instanceof FeVariant
                        && $requestArguments['feVariant']->getVariantData() === [
                            [
                                'sku' => 'firstname',
                                'title' => 'firstname',
                                'value' => 'Jane',
                            ],
                        ]
                )
            )
            ->willReturn($this->createProduct());

        $event = new AddToCartFinisherEvent(
            [
                'productType' => 'CartEvents',
                'eventDateId' => '3',
                'firstname' => 'Jane',
                'honeypot' => '',
            ],
            $this->cart
        );
        $this->createSubject()($event);
    }

    private function createProduct(): Product
    {
        return new Product(
            'CartEvents',
            3,
            'event-date',
            'Event date',
            10.0,
            new TaxClass(1, '19', 0.19, 'normal'),
            1
        );
    }

    private function createSubject(): AddToCartFinisher
    {
        $extensionConfiguration = self::createStub(ExtensionConfiguration::class);
        $extensionConfiguration->method('get')->willReturn('1');

        return new AddToCartFinisher($extensionConfiguration, $this->productFactory);
    }
}
