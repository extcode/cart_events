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

class EventListCest
{
    public function testListAndDetailViewForNonBookableEvent(Tester $I): void
    {
        $I->amOnUrl('http://127.0.0.1:8080/events/');

        $I->see('Event 1');
        $I->see('Teaser 1');

        $I->dontSee('Event 4');

        $I->click('Event 1');
        $I->see('Event 1', 'h1');
        $I->see('31.07.2024 10:00');
        $I->see('This event date can not be booked.');
    }

    public function testListAndDetailViewForBookableEventWithoutPriceCategories(Tester $I): void
    {
        $I->amOnUrl('http://127.0.0.1:8080/events/');

        $I->see('Event 2');
        $I->see('Teaser 2');

        $I->dontSee('Event 4');

        $I->click('Event 2');
        $I->see('Event 2', 'h1');
        $I->see('31.07.2024 10:00');

        $I->dontSee('This event date can not be booked.');
        $I->seeElement("input[name='tx_cart_cart[quantity]']");
        $I->seeElement('input[type="submit"]');
    }
}
