<?php

namespace Extcode\CartEvents\Tests\Functional\Domain\Model\Cart;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Codappix\Typo3PhpDatasets\TestingFramework;
use Extcode\Cart\Domain\Model\Cart\TaxClass;
use Extcode\CartEvents\Domain\Model\Cart\ProductFactory;
use Extcode\CartEvents\Domain\Repository\EventDateRepository;
use Extcode\CartEvents\Domain\Repository\PriceCategoryRepository;
use Extcode\CartEvents\Exception\NotBookableException;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

#[CoversClass(ProductFactory::class)]
class ProductFactoryTest extends FunctionalTestCase
{
    use TestingFramework;

    public function setUp(): void
    {
        $this->testExtensionsToLoad[] = 'extcode/cart';
        $this->testExtensionsToLoad[] = 'extcode/cart-events';

        parent::setUp();

        $this->importPHPDataSet(__DIR__ . '/../../../../Fixtures/PagesDatabase.php');
        $this->importPHPDataSet(__DIR__ . '/../../../../Fixtures/EventsDatabase.php');
    }

    #[Test]
    public function throwExceptionWithoutQuantityArgument(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(1741700244);
        $this->expectExceptionMessage('Quantity argument is missing');

        $productFactory = $this->getProductFactory();
        $productFactory->createProductFromRequestArguments(
            [],
            [],
            false
        );
    }

    #[Test]
    public function throwExceptionWithQuantityArgumentLowerThanZero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(1741692900);
        $this->expectExceptionMessage('Quantity argument is invalid');

        $productFactory = $this->getProductFactory();
        $productFactory->createProductFromRequestArguments(
            [
                'quantity' => -1,
            ],
            [],
            false
        );
    }

    #[Test]
    public function throwExceptionWithoutEventDate(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(1741700304);
        $this->expectExceptionMessage('Event date argument is missing');

        $productFactory = $this->getProductFactory();
        $productFactory->createProductFromRequestArguments(
            [
                'quantity' => 1,
            ],
            [],
            false
        );
    }

    #[Test]
    public function throwExceptionWithNonNumericEventDate(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(1741692831);
        $this->expectExceptionMessage('Event date argument is invalid');

        $productFactory = $this->getProductFactory();
        $productFactory->createProductFromRequestArguments(
            [
                'quantity' => 1,
                'eventDate' => 'a',
            ],
            [],
            false
        );
    }

    #[Test]
    public function throwExceptionWithNotExistingEventDate(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(1741693220);
        $this->expectExceptionMessage('Event date not found');

        $productFactory = $this->getProductFactory();
        $productFactory->createProductFromRequestArguments(
            [
                'quantity' => 1,
                'eventDate' => 1000,
            ],
            [],
            false
        );
    }

    #[Test]
    public function throwExceptionWithNotBookableExistingEventDate(): void
    {
        $this->expectException(NotBookableException::class);
        $this->expectExceptionCode(1741693273);
        $this->expectExceptionMessage('Event date not bookable');

        $productFactory = $this->getProductFactory();
        $productFactory->createProductFromRequestArguments(
            [
                'quantity' => 1,
                'eventDate' => 1,
            ],
            [],
            false
        );
    }

    #[Test]
    public function getCartProductForValidQuantityAndBookableEventDate(): void
    {
        $productFactory = $this->getProductFactory();
        $product = $productFactory->createProductFromRequestArguments(
            [
                'quantity' => 1,
                'eventDate' => 3,
            ],
            [
                1 => new TaxClass(
                    1,
                    '19 %',
                    0.19,
                    'normal'
                ),
            ],
            false
        );

        self::assertSame(
            1,
            $product->getQuantity()
        );
        self::assertSame(
            3,
            $product->getProductId()
        );
        self::assertSame(
            'event-3 - eventdate-3-1',
            $product->getSku()
        );
        self::assertSame(
            'Event 3 - Eventdate 3.1',
            $product->getTitle()
        );

        self::assertSame(
            29.99,
            $product->getPrice()
        );
        self::assertSame(
            29.99,
            $product->getGross()
        );
        self::assertSame(
            25.201680672268907,
            $product->getNet()
        );
        self::assertSame(
            4.7883193277310925,
            $product->getTax()
        );

        self::assertTrue(
            $product->isVirtualProduct()
        );

        self::assertFalse(
            $product->isHandleStock()
        );
    }

    private function getProductFactory(): ProductFactory
    {
        return GeneralUtility::makeInstance(
            ProductFactory::class,
            GeneralUtility::makeInstance(EventDateRepository::class),
            GeneralUtility::makeInstance(PriceCategoryRepository::class),
        );
    }
}
