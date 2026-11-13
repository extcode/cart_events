# Cart Events

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/f7809fa0f2ab40118e263cb714212d13)](https://www.codacy.com/app/extcode/cart_events?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=extcode/cart_events&amp;utm_campaign=Badge_Grade)

Cart is a small but powerful extension which "solely" adds a shopping cart to your TYPO3 installation.
Cart Events provides an own data storage for events.

## 1. Features

* It provides events and their event dates which can be created in the TYPO3 backend.
* The data for those events are stored in own data tables.
* The data fields of the events fit many use cases for seminars, workshops, theatre
  performances or generally date-related seat reservations.
* The events and their dates can be displayed on the website with a list view and a
  detail view.
* It is possible to limit the number of bookable seats per event date or per price
  category of an event date.
* As it extends EXT:cart are the products compatible with EXT:cart and can
  therefore be be purchased with the cart functionality of EXT:cart.

## 2. Installation

### 2.1 Installation

#### Installation using Composer

The recommended way to install the extension is by using [Composer][2]. In your Composer based TYPO3 project root, just do `composer require extcode/cart-events`.

#### Installation as extension from TYPO3 Extension Repository (TER)

Download and install the extension with the extension manager module.

### 2.2 Upgrade

**Attention**, Before updating to a new minor version or upgrading to a new major version, be sure to check the
changelog section in the documentation.
Sometimes minor versions also result in minor adjustments to own templates or configurations.

## 3. Administration

## 3.1 Compatibility and supported Versions

The compatibility with TYPO3 and PHP and the support status of all versions are listed in the
[official extension documentation in chapter supported versions](https://docs.typo3.org/p/extcode/cart-events/main/en-us/Introduction/SupportedVersions/Index.html).

If you need extended support for features and bug fixes outside of the currently supported versions,
we are happy to offer paid services.

### 3.2. Changelog

Please have a look into the [official extension documentation in changelog chapter](https://docs.typo3.org/p/extcode/cart-events/main/en-us/Changelog/Index.html)

## 4. Sponsoring

* Ask for an invoice.
* [PayPal.Me](https://paypal.me/extcart)

[1]: https://docs.typo3.org/typo3cms/extensions/cart_events/
[2]: https://getcomposer.org/
