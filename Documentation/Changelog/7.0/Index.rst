.. include:: ../../Includes.rst.txt

7.0 Changes
===========

**Table of contents**

.. contents::
   :local:
   :depth: 1

7.0.2
-----

Bugfixes
~~~~~~~~

*  Pass errors as FlashMessages in RetrieveProductsFromRequest (`#144 <https://github.com/extcode/cart_events/pull/144>`__)

Tasks
~~~~~

*  Check spelling with typos (`#142 <https://github.com/extcode/cart_events/pull/142>`__)
*  List the changes of each release in the changelog

7.0.1
-----

Bugfixes
~~~~~~~~

*  Add products from forms via AddToCartFinisherEvent (`#138 <https://github.com/extcode/cart_events/pull/138>`__)

7.0.0
-----

Compatibility
~~~~~~~~~~~~~

*  TYPO3 ^14.3 (was ^13.4)
*  PHP ~8.2.0 || ~8.3.0 || ~8.4.0 || ~8.5.0 (was ~8.2.0 || ~8.3.0 || ~8.4.0)
*  extcode/cart ^12.0 (was ^11.3)

Breaking
~~~~~~~~

*  :doc:`#125 - Make all event listeners final <Breaking-125-MakeEventListenersFinal>`
*  :doc:`#125 - Remove slug updater <Breaking-125-RemoveSlugUpdater>`

.. toctree::
   :hidden:
   :glob:

   *-*
