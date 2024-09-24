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

class EventListCest
{
    #[Test]
    public function listForEvents(Tester $I): void
    {
        $I->amOnUrl('http://127.0.0.1:8080/events/');

        $I->see('Event 1');
        $I->see('Teaser 1');
        $I->see('Event 2');
        $I->see('Teaser 2');
        $I->see('Event 3');
        $I->see('Teaser 3');

        $I->dontSee('Event 4');
    }

    #[Test]
    public function detailViewForNonBookableEvent(Tester $I): void
    {
        $I->amOnUrl('http://127.0.0.1:8080/events/');

        $I->see('Event 1');
        $I->click('Event 1');

        $I->see('Event 1', 'h1');
        $I->see('31.07.2024 10:00');
        $I->see('This event date can not be booked.');
    }

    #[Test]
    public function detailViewForBookableEventWithOneEventdateWithoutPriceCategories(Tester $I): void
    {
        $I->amOnUrl('http://127.0.0.1:8080/events/');

        $I->see('Event 2');
        $I->click('Event 2');

        $I->see('Event 2', 'h1');
        $I->see('Eventdate 2', 'h2');
        $I->see('31.07.2024 10:00 - 31.07.2024 12:00');
        $I->see('19,99 €');

        $I->dontSee('This event date can not be booked.');
        $I->seeElement("input[name='tx_cart_cart[quantity]']");
        $I->seeElement('input[type="submit"]');
    }

    #[Test]
    public function detailViewForBookableEventWithTwoOrMoreEventdatesWithoutPriceCategories(Tester $I): void
    {
        $I->amOnUrl('http://127.0.0.1:8080/events/');

        $I->see('Event 3');
        $I->click('Event 3');

        $I->see('Event 3', 'h1');
        $I->see('Eventdate 3.1', 'h2');
        $I->see('31.07.2024 10:00 - 31.07.2024 12:00');
        $I->see('29,99 €');

        $I->see('Eventdate 3.2', 'h2');
        $I->see('15.08.2024 10:00 - 15.08.2024 11:00');
        $I->see('32,99 €');

        $I->see('Eventdate 3.3', 'h2');
        $I->see('16.08.2024 12:00 - 16.08.2024 13:30');
        $I->see('34,99 €');

        $I->dontSee('This event date can not be booked.');
    }

    #[Test]
    public function addBookableEventToCart(Tester $I): void
    {
        $I->amOnUrl('http://127.0.0.1:8080/cart/');
        $I->dontSee('Event 2');

        $I->amOnUrl('http://127.0.0.1:8080/events/');

        $I->see('Event 2');
        $I->click('Event 2');
        $I->see('19,99 €');

        $I->dontSee('This event date can not be booked.');
        $I->seeElement("input[name='tx_cart_cart[quantity]']");
        $I->seeInField("input[name='tx_cart_cart[quantity]']", '1');
        $I->seeElement('input[type="submit"]');
        $I->click('input[type="submit"]');
        $I->see('Item was added to cart.');

        $I->fillField("input[name='tx_cart_cart[quantity]']", '2');
        $I->click('input[type="submit"]');
        $I->see('2 Items were added to cart.');

        $I->amOnUrl('http://127.0.0.1:8080/cart/');
        $I->see('Event 2');
        $I->see('19,99 €', '.checkout-product-table tr:nth-child(1) td:nth-child(2)');
        $I->seeElement("input[name='tx_cart_cart[quantities][CartEvents_2]']");
        $I->seeInField("input[name='tx_cart_cart[quantities][CartEvents_2]']", '3');
        $I->see('71,36 €', '.checkout-product-table tr:nth-child(1) td:nth-child(4)');
    }
}
