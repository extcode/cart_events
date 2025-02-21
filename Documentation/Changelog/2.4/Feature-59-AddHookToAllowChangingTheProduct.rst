.. include:: ../../Includes.rst.txt

=====================================================
Feature: #59 - Add Hook to Allow Changing the Product
=====================================================

See :issue:`59`

Description
===========

The new hook allows you to modify a product that has been newly loaded from the database before passing it to the
shopping cart.
A similar hook already exists in extcode/cart, but it is more unspecific and theoretically accesses all products.
The new hook will be introduced to apply the temporarily lowered sales tax in Germany to events in the shopping cart.

The hook can be used as follows:

::

    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cart_events']['getProductFromEventDate'][1592547127] =
        \Extcode\CartCovid19Tax\Hooks\TaxClassOverride::class;

The class must then provide the method `changeProductFromEventDate` and receives \Extcode\Cart\Domain\Model\Cart\Product
and an array as parameters.
In the array the \Extcode\Cart\Domain\Model\Cart\Cart and \Extcode\CartEvents\Domain\Model\EventDate are currently passed.

::

   /**
    * change the product
    *
    * @param Product $product
    * @param array $params
    */
   public function changeProductFromEventDate(Product $product, array $params): void
   {
       /** @var Cart $cart */
       $cart = $params['cart'];

       /** @var EventDate $eventDate */
       $eventDate = $params['eventDate'];

       // TODO: change the product
   }

.. index:: API, Backend
