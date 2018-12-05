# Cart Events

[![Build Status](https://travis-ci.org/extcode/cart_events.svg?branch=master)](https://travis-ci.org/extcode/cart_events)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/f7809fa0f2ab40118e263cb714212d13)](https://www.codacy.com/app/extcode/cart_events?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=extcode/cart_events&amp;utm_campaign=Badge_Grade)

Cart is a small but powerful extension which "solely" adds a shopping cart to your TYPO3 installation.
Cart Events provides an own data storage for events. Events can be offered via a list and detail view and can be purchased via cart function of the Cart extension.

## 1. Features

-
-
-

## 2. Installation

### 2.1 Installation

#### Installation using Composer

The recommended way to install the extension is by using [Composer][2]. In your Composer based TYPO3 project root, just do `composer require extcode/cart-events`. 

#### Installation as extension from TYPO3 Extension Repository (TER)

Download and install the extension with the extension manager module.

### 2.2 Upgrade

**If upgrading from cart version 4.8.1 or earlier: Please read the documentation very carefully! Please make a backup of your filesystem
and database!** If possible test the update in a test copy of your TYPO3 instance.

## 3. Administration

## 3.1 Compatibility and supported Versions

| Cart Events   | TYPO3      | PHP       | Support/Development                                                                      |
| ------------- | ---------- | ----------|------------------------------------------------------------------------------------------|
| 2.x.x         | 9.5        | 7.2       | Features, Bugfixes, Security Updates                                                     |
| 1.x.x         | 8.7        | 7.0 - 7.2 | Features _(in certain circumstances with feature toogle)_, Bugfixes, Security Updates    |

### 3.2. Changelog

Please have a look into the [official extension documentation in changelog chapter](https://docs.typo3.org/typo3cms/extensions/cart_events/Misc/Changelog/Index.html)

### 3.3. Release Management

News uses **semantic versioning** which basically means for you, that
- **bugfix updates** (e.g. 1.0.0 => 1.0.1) just includes small bugfixes or security relevant stuff without breaking changes.
- **minor updates** (e.g. 1.0.0 => 1.1.0) includes new features and smaller tasks without breaking changes.
- **major updates** (e.g. 1.0.0 => 2.0.0) breaking changes wich can be refactorings, features or bugfixes.

## 4. Sponsoring

*  Ask for an invoice.
*  [Patreon](https://patreon.com/ext_cart)
*  [PayPal.Me](https://paypal.me/extcart)

[1]: https://docs.typo3.org/typo3cms/extensions/cart_events/
[2]: https://getcomposer.org/