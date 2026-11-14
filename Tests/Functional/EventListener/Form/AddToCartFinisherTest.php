<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Tests\Functional\EventListener\Form;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Codappix\Typo3PhpDatasets\TestingFramework;
use Extcode\Cart\Domain\Model\Cart\Cart;
use Extcode\Cart\Domain\Model\Cart\Product;
use Extcode\Cart\Domain\Model\Cart\TaxClass;
use Extcode\Cart\Event\Form\AddToCartFinisherEvent;
use Extcode\CartEvents\EventListener\Form\AddToCartFinisher;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

#[CoversClass(AddToCartFinisher::class)]
class AddToCartFinisherTest extends FunctionalTestCase
{
    use TestingFramework;

    public function setUp(): void
    {
        $this->testExtensionsToLoad[] = 'extcode/cart';
        $this->testExtensionsToLoad[] = 'extcode/cart-events';

        parent::setUp();

        $this->importPHPDataSet(__DIR__ . '/../../../Fixtures/PagesDatabase.php');
        $this->importPHPDataSet(__DIR__ . '/../../../Fixtures/EventsDatabase.php');
    }

    #[Test]
    public function dispatchedEventContainsProductForBookableEventDate(): void
    {
        $event = new AddToCartFinisherEvent(
            [
                'productType' => 'CartEvents',
                'eventDateId' => '3',
                'priceCategoryId' => '',
                'firstname' => 'Jane',
            ],
            $this->getCart()
        );

        $this->get(EventDispatcherInterface::class)->dispatch($event);

        self::assertSame([], $event->getErrors());

        $products = $event->getCartProducts();
        self::assertCount(1, $products);

        $product = $products[0];
        self::assertInstanceOf(Product::class, $product);
        self::assertSame(3, $product->getProductId());
        self::assertSame(1, $product->getQuantity());
        self::assertSame('Event 3 - Eventdate 3.1', $product->getTitle());
        self::assertSame(1, $product->getMaxNumberInCart());
        self::assertSame(1, $product->getMinNumberInCart());
        self::assertSame(
            [
                [
                    'sku' => 'firstname',
                    'title' => 'firstname',
                    'value' => 'Jane',
                ],
            ],
            $product->getFeVariant()?->getVariantData()
        );
    }

    #[Test]
    public function dispatchedEventContainsErrorForNotBookableEventDate(): void
    {
        $event = new AddToCartFinisherEvent(
            [
                'productType' => 'CartEvents',
                'eventDateId' => '1',
            ],
            $this->getCart()
        );

        $this->get(EventDispatcherInterface::class)->dispatch($event);

        self::assertSame([], $event->getCartProducts());
        self::assertNotSame([], $event->getErrors());
    }

    private function getCart(): Cart
    {
        return new Cart(
            [
                1 => new TaxClass(
                    1,
                    '19 %',
                    0.19,
                    'normal'
                ),
            ]
        );
    }
}
