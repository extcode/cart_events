<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Tests\Unit\Domain\Model;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\CartEvents\Domain\Model\Category;
use Extcode\CartEvents\Tests\ObjectAccess;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversClass(Category::class)]
class CategoryTest extends UnitTestCase
{
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = new Category();
    }

    protected function tearDown(): void
    {
        unset($this->category);

        parent::tearDown();
    }

    #[Test]
    public function categoryExtendsExtbaseCategoryModel(): void
    {
        self::assertInstanceOf(\TYPO3\CMS\Extbase\Domain\Model\Category::class, $this->category);
    }

    #[Test]
    public function getCartEventListPidReturnsInitialValueNull(): void
    {
        $category = new Category();

        self::assertNull(
            $category->getCartEventListPid()
        );

        ObjectAccess::setProperty($category, 'cartEventListPid', 31);

        self::assertSame(
            31,
            $category->getCartEventListPid()
        );
    }

    #[Test]
    public function getCartEventShowPidReturnsInitialValueNull(): void
    {
        $category = new Category();

        self::assertNull(
            $category->getCartEventShowPid()
        );

        ObjectAccess::setProperty($category, 'cartEventShowPid', 31);

        self::assertSame(
            31,
            $category->getCartEventShowPid()
        );
    }
}
