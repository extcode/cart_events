.. include:: ../../Includes.rst.txt

================================================
Breaking: #125 - Make all event listeners final
================================================

See `Pull Request 125 <https://github.com/extcode/cart_events/pull/125>`__

Description
===========

All event listeners are `final` and `readonly` now. Their properties
and helper methods are `private`.

`Classes/EventListener/CheckProductAvailability.php`
`Classes/EventListener/Order/Stock/FlushCache.php`
`Classes/EventListener/Order/Stock/HandleStock.php`
`Classes/EventListener/RetrieveProductsFromRequest.php`

The identifiers of the event listeners are unchanged.

Affected Installations
======================

All installations extending one of the event listeners.

Migration
=========

Copy the code of the event listener into your own class and add your
changes there. Register your own class as event listener in the service
configuration of your extension.
