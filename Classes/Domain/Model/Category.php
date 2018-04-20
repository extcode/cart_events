<?php

namespace Extcode\CartEvents\Domain\Model;

/**
 * Category Model
 *
 * @author Daniel Lorenz <ext.cart@extco.de>
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
