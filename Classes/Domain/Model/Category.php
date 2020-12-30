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
    /**
     * Cart Event List Pid
     *
     * @var int
     */
    protected $cartEventListPid;

    /**
     * Cart Event Single Pid
     *
     * @var int
     */
    protected $cartEventShowPid;

    /**
     * Returns Cart Event List Pid
     *
     * @return int
     */
    public function getCartEventListPid()
    {
        return $this->cartEventListPid;
    }

    /**
     * Returns Cart Event Single Pid
     *
     * @return int
     */
    public function getcartEventShowPid()
    {
        return $this->cartEventShowPid;
    }
}
