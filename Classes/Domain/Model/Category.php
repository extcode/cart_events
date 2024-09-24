<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Domain\Model;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

class Category extends \TYPO3\CMS\Extbase\Domain\Model\Category
{
    protected ?int $cartEventListPid = null;

    protected ?int $cartEventShowPid = null;

    public function getCartEventListPid(): ?int
    {
        return $this->cartEventListPid;
    }

    public function getCartEventShowPid(): ?int
    {
        return $this->cartEventShowPid;
    }
}
