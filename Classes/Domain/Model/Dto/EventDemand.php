<?php
declare(strict_types=1);

namespace Extcode\CartEvents\Domain\Model\Dto;

/**
 * Data Transfer Object Event
 *
 * @author Daniel Lorenz <ext.cart@extco.de>
 */
class EventDemand extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Sku
     *
     * @var string
     */
    protected $sku = '';

    /**
     * Title
     *
     * @var string
     */
    protected $title = '';

    /**
     * Categories
     *
     * @var array
     */
    protected $categories = [];

    /**
     * Order
     *
     * @var string
     */
    protected $order = '';

    /**
     * Limit
     *
     * @var int
     */
    protected $limit = 0;

    /**
     * Action
     *
     * @var string
     */
    protected $action = '';

    /**
     * Class
     *
     * @var string
     */
    protected $class = '';

    /**
     * Returns sku
     *
     * @return string
     */
    public function getSku() : string
    {
        return $this->sku;
    }

    /**
     * Sets sku
     *
     * @param string $sku
     */
    public function setSku(string $sku)
    {
        $this->sku = $sku;
    }

    /**
     * Returns title
     *
     * @return string
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * Sets title
     *
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return array
     */
    public function getCategories() : array
    {
        return $this->categories;
    }

    /**
     * @param array $categories
     */
    public function setCategories(array $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Returns order
     *
     * @return string
     */
    public function getOrder() : string
    {
        return $this->order;
    }

    /**
     * Sets order
     *
     * @param string $order
     */
    public function setOrder(string $order)
    {
        $this->order = $order;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit(int $limit)
    {
        $this->limit = $limit;
    }

    /**
     * Returns action
     *
     * @return string
     */
    public function getAction() : string
    {
        return $this->action;
    }

    /**
     * Sets action
     *
     * @param string $action
     */
    public function setAction(string $action)
    {
        $this->action = $action;
    }
    /**
     * Returns class
     *
     * @return string
     */
    public function getClass() : string
    {
        return $this->class;
    }

    /**
     * Sets class
     *
     * @param string $class
     */
    public function setClass(string $class)
    {
        $this->class = $class;
    }

    /**
     * Sets action and class
     *
     * @param string $action
     * @param string $controller
     */
    public function setActionAndClass(string $action, string $controller)
    {
        $this->action = $action;
        $this->class = $controller;
    }
}
