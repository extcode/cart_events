<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Tests;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use ReflectionProperty;

class ObjectAccess
{
    public static function setProperty(object $instance, string $propertyName, mixed $value): void
    {
        $reflection = new ReflectionProperty($instance::class, $propertyName);
        $reflection->setValue($instance, $value);
    }
}
