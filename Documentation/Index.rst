.. include:: Includes.rst.txt

.. _start:

===========
Cart Events
===========

Cart Products - extend EXT:cart with products
=============================================

.. image:: Images/cart_events_logo.png
   :height: 100
   :width: 100


.. only:: html

   :Extension key:
      cart_events

   :Package name:
      extcode/cart-events

   :Version:
      |release|

   :Language:
      en

   :Author:
      Daniel Gohlke & Contributors

   :License:
      This document is published under the
      `Open Publication License <https://www.opencontent.org/openpub/>`__.

   :Rendered:
      |today|

-----

*Cart Events* needs to be used together with :t3ext:`cart`.

* EXT:cart itself is only the base for a webshop.
* EXT:cart_events provides events which can be created in the TYPO3 backend.

  * Those events fit many use cases for seminars, workshops, theatre performances or generally date-related seat reservations.
  * The events and their dates can be displayed on the website with a list view and a detail view.
  * As said does it extend EXT:cart so those products can be purchased with the cart of EXT:cart.

-----

..  card-grid::
    :columns: 1
    :columns-md: 2
    :gap: 4
    :class: pb-4
    :card-height: 100

    ..  card:: :ref:`Introduction <introduction>`

        Introduction to the extension, general information.

    ..  card:: :ref:`For Administrators <administrator>`

        Install the extension and configure it correctly.

    ..  card:: :ref:`Changelog <changelog>`

        Changes of this extension during updates.

.. toctree::
   :maxdepth: 1
   :titlesonly:
   :hidden:
   :maxdepth: 5
   :titlesonly:

   Introduction/Index
   Administrator/Index
   Changelog/Index
