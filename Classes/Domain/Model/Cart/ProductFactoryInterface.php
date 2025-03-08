<?php

namespace Extcode\CartEvents\Domain\Model\Cart;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\Cart\Domain\Model\Cart\Product;

interface ProductFactoryInterface
{
    public function createProductFromRequestArguments(array $requestArguments, array $taxClasses, bool $isNetPrice): Product;
}
