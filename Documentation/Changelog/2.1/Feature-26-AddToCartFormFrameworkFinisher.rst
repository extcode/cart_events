.. include:: ../../Includes.txt

====================================================
Feature: #26 - Add addToCart form framework finisher
====================================================

See :issue:`26`

Description
===========

In order to allow to individualize events when adding them to the cart, a new addToCart finisher for the form framework
allow to load a form and submit the form with the selected event. The fields are handled as frontend variants in the
cart product. They have no intended impact on the price or stock handling.

An example form template 'Cart Events - Example' can be used to create different forms for different events.
It can also serve as a template for manually creating forms.

.. IMPORTANT::
   An update of the database is required. As this field is new there are no problems to be expected.

.. NOTE::
   The form is currently loaded via AJAX into a <div> with the data-attribute **data-add-to-cart="result"**.
   There will be a more generic solution to load a modal view as well.

.. index:: API, Frontend, Backend, JavaScript