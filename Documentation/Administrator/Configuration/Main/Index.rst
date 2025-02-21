.. include:: ../../../Includes.rst.txt

==================
Main Configuration
==================

::

    plugin.tx_cartevents {
        settings {
            format.currency < plugin.tx_cart.settings.format.currency

            addToCartByAjax = {$plugin.tx_cart.settings.addToCartByAjax}
        }
    }

.. container:: table-row

   Property
      plugin.tx_cartevents.settings.format.currency
   Data type
      array
   Description
      Configures how prices should be formated in frontend. The \Extcode\Cart\ViewHelpers\Format\CurrencyViewHelper use
      this global setting.
   Default
      The TypoScript template copy the setting from settings of the cart extension.


.. container:: table-row

   Property
      plugin.tx_cartevents.settings.addToCartByAjax
   Data type
      int
   Description
      Activates the option to add events via AJAX action. There is no forwarding to the shopping cart page.
      The response can used to display messages or update the MiniCart-Plugin.
   Default
      The TypoScript template use the setting defined by the constant of the cart extension.