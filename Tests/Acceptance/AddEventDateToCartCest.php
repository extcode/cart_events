<?php

declare(strict_types=1);

namespace Extcode\CartEvents\Tests\Acceptance;

/*
 * This file is part of the package extcode/cart-events.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Extcode\CartEvents\Tests\Acceptance\Support\Tester;
use PHPUnit\Framework\Attributes\Test;

class AddEventDateToCartCest
{
    #[Test]
    public function addBookableEventToCart(Tester $I): void
    {
        $I->wantToTest('I can add a bookable event date to the cart.');

        $I->amOnUrl('http://127.0.0.1:8080/cart/');
        $I->dontSee('Event 2');

        $I->amOnUrl('http://127.0.0.1:8080/events/');

        $I->seeLink('Event 2', '/events?tx_cartevents_listevents%5Baction%5D=show&tx_cartevents_listevents%5Bevent%5D=2&cHash=c02777b6ed45d4187afc3fd7f1a42b85');
        $I->click('Event 2');
        $I->see('19,99 €');

        $I->dontSee('This event date can not be booked.');
        $I->dontSee('#event-date-2 .form-message .form-success');
        $I->dontSee('#event-date-2 .form-message .form-error');

        $I->wantTo('Add the first event date with quantity of 1 to cart.');
        $I->seeElement("input[name='tx_cart_cart[quantity]']");
        $I->seeInField("input[name='tx_cart_cart[quantity]']", '1');
        $I->seeElement('input[type="submit"]');
        $I->click('input[type="submit"]');
        $I->see('Item was added to cart.', '#event-date-2 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-2 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-2 .form-message .form-error');

        $I->wantTo('Add the first event date with quantity of 2 to cart.');
        $I->fillField("input[name='tx_cart_cart[quantity]']", '2');
        $I->click('input[type="submit"]');
        $I->see('2 Items were added to cart.', '#event-date-2 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-2 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-2 .form-message .form-error');

        $I->amOnUrl('http://127.0.0.1:8080/cart/');
        $I->see('Event 2 - Eventdate 2');
        $I->see('19,99 €', '.checkout-product-table tr:nth-child(1) td:nth-child(2)');
        $I->seeElement("input[name='tx_cart_cart[quantities][CartEvents_2]']");
        $I->seeInField("input[name='tx_cart_cart[quantities][CartEvents_2]']", '3');
        $I->see('71,36 €', '.checkout-product-table tr:nth-child(1) td:nth-child(4)');
    }

    #[Test]
    public function addBookableEventDateWithoutSeatHandlingToCart(Tester $I): void
    {
        $I->wantToTest('I can add a bookable event date without seat handling to the cart.');

        $I->amOnUrl('http://127.0.0.1:8080/cart/');
        $I->dontSee('Event 2');

        $I->amOnUrl('http://127.0.0.1:8080/events/');

        $I->seeLink('Event 2', '/events?tx_cartevents_listevents%5Baction%5D=show&tx_cartevents_listevents%5Bevent%5D=2&cHash=c02777b6ed45d4187afc3fd7f1a42b85');
        $I->click('Event 2');
        $I->see('19,99 €');

        $I->dontSee('This event date can not be booked.');
        $I->dontSee('#event-date-2 .form-message .form-success');
        $I->dontSee('#event-date-2 .form-message .form-error');

        $I->wantTo('Add the first event date with quantity of 1000 to cart.');
        $I->seeElement("input[name='tx_cart_cart[quantity]']");
        $I->seeInField("input[name='tx_cart_cart[quantity]']", '1');
        $I->fillField("input[name='tx_cart_cart[quantity]']", '1000');
        $I->seeElement('input[type="submit"]');
        $I->click('input[type="submit"]');
        $I->see('1000 Items were added to cart.', '#event-date-2 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-2 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-2 .form-message .form-error');

        $I->amOnUrl('http://127.0.0.1:8080/cart/');
        $I->see('Event 2 - Eventdate 2');
        $I->see('19,99 €', '.checkout-product-table tr:nth-child(1) td:nth-child(2)');
        $I->seeElement("input[name='tx_cart_cart[quantities][CartEvents_2]']");
        $I->seeInField("input[name='tx_cart_cart[quantities][CartEvents_2]']", '1000');
        $I->see('23.788,10 €', '.checkout-product-table tr:nth-child(1) td:nth-child(4)');
    }

    #[Test]
    public function addBookableEventDateWithAvailableNumberOfSeatToCart(Tester $I): void
    {
        $I->wantToTest('I can add a bookable event date with available number of seat to the cart.');

        $I->amOnUrl('http://127.0.0.1:8080/cart/');
        $I->dontSee('Event 3');

        $I->amOnUrl('http://127.0.0.1:8080/events/');

        $I->seeLink('Event 3', '/events?tx_cartevents_listevents%5Baction%5D=show&tx_cartevents_listevents%5Bevent%5D=3&cHash=d1b28fe9400d030f4a10cd177e517670');
        $I->click('Event 3');

        $I->dontSee('This event date can not be booked.');
        $I->dontSee('#event-date-3 .form-message .form-success');
        $I->dontSee('#event-date-3 .form-message .form-error');
        $I->dontSee('#event-date-4 .form-message .form-success');
        $I->dontSee('#event-date-4 .form-message .form-error');
        $I->dontSee('#event-date-5 .form-message .form-success');
        $I->dontSee('#event-date-5 .form-message .form-error');

        $I->wantTo('Add the first event date with quantity of 1 to cart.');
        $I->seeElement("#event-date-3 input[name='tx_cart_cart[quantity]']");
        $I->seeInField("#event-date-3 input[name='tx_cart_cart[quantity]']", '1');
        $I->seeElement('#event-date-3 input[type="submit"]');
        $I->click('#event-date-3 input[type="submit"]');
        $I->see('Item was added to cart.', '#event-date-3 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-3 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-3 .form-message .form-error');

        $I->wantTo('Add the first event date with quantity of 2 to cart.');
        $I->fillField("#event-date-3 input[name='tx_cart_cart[quantity]']", '2');
        $I->click('#event-date-3 input[type="submit"]');
        $I->see('2 Items were added to cart.', '#event-date-3 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-3 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-3 .form-message .form-error');

        $I->amOnUrl('http://127.0.0.1:8080/cart/');
        $I->see('Event 3 - Eventdate 3.1');
        $I->see('29,99 €', '.checkout-product-table tr:nth-child(1) td:nth-child(2)');
        $I->seeElement("input[name='tx_cart_cart[quantities][CartEvents_3]']");
        $I->seeInField("input[name='tx_cart_cart[quantities][CartEvents_3]']", '3');
        $I->see('107,06 €', '.checkout-product-table tr:nth-child(1) td:nth-child(4)');
    }

    #[Test]
    public function addDifferentBookableEventDatesWithAvailableNumberOfSeatToCart(Tester $I): void
    {
        $I->wantToTest('I can add different bookable event dates with available number of seat to the cart.');

        $I->amOnUrl('http://127.0.0.1:8080/cart/');
        $I->dontSee('Event 3');

        $I->amOnUrl('http://127.0.0.1:8080/events/');

        $I->seeLink('Event 3', '/events?tx_cartevents_listevents%5Baction%5D=show&tx_cartevents_listevents%5Bevent%5D=3&cHash=d1b28fe9400d030f4a10cd177e517670');
        $I->click('Event 3');

        $I->dontSee('This event date can not be booked.');
        $I->dontSee('#event-date-3 .form-message .form-success');
        $I->dontSee('#event-date-3 .form-message .form-error');
        $I->dontSee('#event-date-4 .form-message .form-success');
        $I->dontSee('#event-date-4 .form-message .form-error');
        $I->dontSee('#event-date-5 .form-message .form-success');
        $I->dontSee('#event-date-5 .form-message .form-error');

        $I->wantTo('Add the first event date with quantity of 1 to cart.');
        $I->seeElement("#event-date-3 input[name='tx_cart_cart[quantity]']");
        $I->seeInField("#event-date-3 input[name='tx_cart_cart[quantity]']", '1');
        $I->seeElement('#event-date-3 input[type="submit"]');
        $I->click('#event-date-3 input[type="submit"]');
        $I->see('Item was added to cart.', '#event-date-3 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-3 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-3 .form-message .form-error');

        $I->wantTo('Add the second event date with quantity of 2 to cart.');
        $I->seeElement("#event-date-4 input[name='tx_cart_cart[quantity]']");
        $I->seeInField("#event-date-4 input[name='tx_cart_cart[quantity]']", '1');
        $I->fillField("#event-date-4 input[name='tx_cart_cart[quantity]']", '2');
        $I->seeElement('#event-date-4 input[type="submit"]');
        $I->click('#event-date-4 input[type="submit"]');
        $I->see('2 Items were added to cart.', '#event-date-4 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-4 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-4 .form-message .form-error');

        $I->amOnUrl('http://127.0.0.1:8080/cart/');
        $I->see('Event 3 - Eventdate 3.1');
        $I->see('29,99 €', '.checkout-product-table tr:nth-child(1) td:nth-child(2)');
        $I->seeElement("input[name='tx_cart_cart[quantities][CartEvents_3]']");
        $I->seeInField("input[name='tx_cart_cart[quantities][CartEvents_3]']", '1');
        $I->see('35,69 €', '.checkout-product-table tr:nth-child(1) td:nth-child(4)');

        $I->see('Event 3 - Eventdate 3.2');
        $I->see('32,99 €', '.checkout-product-table tr:nth-child(2) td:nth-child(2)');
        $I->seeElement("input[name='tx_cart_cart[quantities][CartEvents_4]']");
        $I->seeInField("input[name='tx_cart_cart[quantities][CartEvents_4]']", '2');
        $I->see('78,52 €', '.checkout-product-table tr:nth-child(2) td:nth-child(4)');
    }

    #[Test]
    public function addBookableEventDateWithAvailableNumberOfSeatButNotMoreToCart(Tester $I): void
    {
        $I->wantToTest('I can add a bookable event date with available number of seat but not more to the cart.');

        $I->amOnUrl('http://127.0.0.1:8080/cart/');
        $I->dontSee('Event 3');

        $I->amOnUrl('http://127.0.0.1:8080/events/');

        $I->seeLink('Event 3', '/events?tx_cartevents_listevents%5Baction%5D=show&tx_cartevents_listevents%5Bevent%5D=3&cHash=d1b28fe9400d030f4a10cd177e517670');
        $I->click('Event 3');

        $I->dontSee('This event date can not be booked.');
        $I->dontSee('#event-date-3 .form-message .form-success');
        $I->dontSee('#event-date-3 .form-message .form-error');
        $I->dontSee('#event-date-4 .form-message .form-success');
        $I->dontSee('#event-date-4 .form-message .form-error');
        $I->dontSee('#event-date-5 .form-message .form-success');
        $I->dontSee('#event-date-5 .form-message .form-error');

        $I->wantTo('Add the first event date with quantity of 10 to cart.');
        $I->seeInField("#event-date-3 input[name='tx_cart_cart[quantity]']", '1');
        $I->fillField("#event-date-3 input[name='tx_cart_cart[quantity]']", '10');
        $I->click('#event-date-3 input[type="submit"]');
        $I->see('Desired number of this item not available.', '#event-date-3 .form-message .form-error');
        $I->waitForElementNotVisible('#event-date-3 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-3 .form-message .form-error');

        $I->wantTo('Add the first event date with quantity of 9 to cart.');
        $I->seeElement("#event-date-3 input[name='tx_cart_cart[quantity]']");
        $I->seeInField("#event-date-3 input[name='tx_cart_cart[quantity]']", '10');
        $I->fillField("#event-date-3 input[name='tx_cart_cart[quantity]']", '9');
        $I->seeElement('#event-date-3 input[type="submit"]');
        $I->click('#event-date-3 input[type="submit"]');
        $I->see('9 Items were added to cart.', '#event-date-3 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-3 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-3 .form-message .form-error');

        $I->wantTo('Add the first event date with quantity of 1 to cart.');
        $I->seeInField("#event-date-3 input[name='tx_cart_cart[quantity]']", '1');
        $I->click('#event-date-3 input[type="submit"]');
        $I->see('Desired number of this item not available.', '#event-date-3 .form-message .form-error');
        $I->waitForElementNotVisible('#event-date-3 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-3 .form-message .form-error');

        $I->amOnUrl('http://127.0.0.1:8080/cart/');
        $I->see('Event 3 - Eventdate 3.1');
        $I->see('29,99 €', '.checkout-product-table tr:nth-child(1) td:nth-child(2)');
        $I->seeElement("input[name='tx_cart_cart[quantities][CartEvents_3]']");
        $I->seeInField("input[name='tx_cart_cart[quantities][CartEvents_3]']", '9');
        $I->see('321,19 €', '.checkout-product-table tr:nth-child(1) td:nth-child(4)');
    }

    #[Test]
    public function addDifferentBookableEventDateWithAvailableNumberOfSeatButNotMoreToCart(Tester $I): void
    {
        $I->wantToTest('I can add different bookable event date with available number of seat but not more to the cart.');

        $I->amOnUrl('http://127.0.0.1:8080/cart/');
        $I->dontSee('Event 3');

        $I->amOnUrl('http://127.0.0.1:8080/events/');

        $I->seeLink('Event 3', '/events?tx_cartevents_listevents%5Baction%5D=show&tx_cartevents_listevents%5Bevent%5D=3&cHash=d1b28fe9400d030f4a10cd177e517670');
        $I->click('Event 3');

        $I->dontSee('This event date can not be booked.');
        $I->dontSee('#event-date-3 .form-message .form-success');
        $I->dontSee('#event-date-3 .form-message .form-error');
        $I->dontSee('#event-date-4 .form-message .form-success');
        $I->dontSee('#event-date-4 .form-message .form-error');
        $I->dontSee('#event-date-5 .form-message .form-success');
        $I->dontSee('#event-date-5 .form-message .form-error');

        $I->wantTo('Add the first event date with quantity of 9 to cart.');
        $I->seeElement("#event-date-3 input[name='tx_cart_cart[quantity]']");
        $I->seeInField("#event-date-3 input[name='tx_cart_cart[quantity]']", '1');
        $I->fillField("#event-date-3 input[name='tx_cart_cart[quantity]']", '9');
        $I->seeElement('#event-date-3 input[type="submit"]');
        $I->click('#event-date-3 input[type="submit"]');
        $I->see('9 Items were added to cart.', '#event-date-3 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-3 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-3 .form-message .form-error');

        $I->wantTo('Add the first event date with quantity of 1 to cart.');
        $I->seeInField("#event-date-3 input[name='tx_cart_cart[quantity]']", '1');
        $I->click('#event-date-3 input[type="submit"]');
        $I->see('Desired number of this item not available.', '#event-date-3 .form-message .form-error');
        $I->waitForElementNotVisible('#event-date-3 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-3 .form-message .form-error');

        $I->wantTo('Add the second event date with quantity of 9 to cart.');
        $I->seeInField("#event-date-4 input[name='tx_cart_cart[quantity]']", '1');
        $I->fillField("#event-date-4 input[name='tx_cart_cart[quantity]']", '9');
        $I->click('#event-date-4 input[type="submit"]');
        $I->see('Desired number of this item not available.', '#event-date-4 .form-message .form-error');
        $I->waitForElementNotVisible('#event-date-4 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-4 .form-message .form-error');

        $I->wantTo('Add the second event date with quantity of 7 to cart.');
        $I->seeElement("#event-date-4 input[name='tx_cart_cart[quantity]']");
        $I->seeInField("#event-date-4 input[name='tx_cart_cart[quantity]']", '9');
        $I->fillField("#event-date-4 input[name='tx_cart_cart[quantity]']", '7');
        $I->seeElement('#event-date-4 input[type="submit"]');
        $I->click('#event-date-4 input[type="submit"]');
        $I->see('7 Items were added to cart.', '#event-date-4 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-4 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-4 .form-message .form-error');

        $I->wantTo('Add the second event date with quantity of 1 to cart.');
        $I->seeElement("#event-date-4 input[name='tx_cart_cart[quantity]']");
        $I->seeInField("#event-date-4 input[name='tx_cart_cart[quantity]']", '1');
        $I->fillField("#event-date-4 input[name='tx_cart_cart[quantity]']", '1');
        $I->seeElement('#event-date-4 input[type="submit"]');
        $I->click('#event-date-4 input[type="submit"]');
        $I->see('Item was added to cart.', '#event-date-4 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-4 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-4 .form-message .form-error');

        $I->wantTo('Add the second event date with quantity of 1 to cart.');
        $I->seeInField("#event-date-4 input[name='tx_cart_cart[quantity]']", '1');
        $I->click('#event-date-4 input[type="submit"]');
        $I->see('Desired number of this item not available.', '#event-date-4 .form-message .form-error');
        $I->waitForElementNotVisible('#event-date-4 .form-message .form-success');
        $I->waitForElementNotVisible('#event-date-4 .form-message .form-error');

        $I->amOnUrl('http://127.0.0.1:8080/cart/');
        $I->see('Event 3 - Eventdate 3.1');
        $I->see('29,99 €', '.checkout-product-table tr:nth-child(1) td:nth-child(2)');
        $I->seeElement("input[name='tx_cart_cart[quantities][CartEvents_3]']");
        $I->seeInField("input[name='tx_cart_cart[quantities][CartEvents_3]']", '9');
        $I->see('321,19 €', '.checkout-product-table tr:nth-child(1) td:nth-child(4)');

        $I->see('Event 3 - Eventdate 3.2');
        $I->see('32,99 €', '.checkout-product-table tr:nth-child(2) td:nth-child(2)');
        $I->seeElement("input[name='tx_cart_cart[quantities][CartEvents_4]']");
        $I->seeInField("input[name='tx_cart_cart[quantities][CartEvents_4]']", '8');
        $I->see('314,06', '.checkout-product-table tr:nth-child(2) td:nth-child(4)');
    }
}
