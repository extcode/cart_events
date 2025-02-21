.. include:: ../../Includes.rst.txt

====================================
Breaking: #36 - Add price categories
====================================

See :issue:`36`

Impact
======

Templates were adapted to realize the selection of price categories in the
output.
In TYPO3 instances where the files below have been modified, adding events
to the shopping cart might not work correctly.

Affected Installations
======================

Instances which use custom partial or templates for:

:file:`EXT:cart/Resource/Private/Partials/Event/CartForm.html`
:file:`EXT:cart/Resource/Private/Partials/Event/Price.html`
:file:`EXT:cart/Resource/Private/Partials/Event/Seats.html`
:file:`EXT:cart/Resource/Private/Templates/Event/Show.html`

or own JavaScript and Stylesheets:

:file:`EXT:cart/Resource/Public/JavaScripts/cart_events.js`
:file:`EXT:cart/Resource/Public/Sheetsheets/cart_events.css`


Migration
=========

If any of the above files have been overwritten, the changes should be applied
accordingly.

.. index:: API, Frontend, Backend, JavaScript
