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

        $I->seeLink('Event 1', '/events?tx_cartevents_listevents%5Baction%5D=show&tx_cartevents_listevents%5Bevent%5D=1&cHash=4e06011cd740d94d7270b73b5e209c7b');
        $I->see('Teaser 1');
        $I->seeLink('Event 2', '/events?tx_cartevents_listevents%5Baction%5D=show&tx_cartevents_listevents%5Bevent%5D=2&cHash=c02777b6ed45d4187afc3fd7f1a42b85');
        $I->see('Teaser 2');
        $I->seeLink('Event 3', '/events?tx_cartevents_listevents%5Baction%5D=show&tx_cartevents_listevents%5Bevent%5D=3&cHash=d1b28fe9400d030f4a10cd177e517670');
        $I->see('Teaser 3');

        $I->dontSee('Event 4');
    }

    #[Test]
    public function detailViewForNonBookableEvent(Tester $I): void
    {
        $I->amOnUrl('http://127.0.0.1:8080/events/');

        $I->seeLink('Event 1', '/events?tx_cartevents_listevents%5Baction%5D=show&tx_cartevents_listevents%5Bevent%5D=1&cHash=4e06011cd740d94d7270b73b5e209c7b');
        $I->click('Event 1');

        $I->see('Event 1', 'h1');
        $I->see('31.07.2024 10:00');
        $I->see('This event date can not be booked.');
    }

    #[Test]
    public function detailViewForBookableEventWithOneEventdateWithoutPriceCategories(Tester $I): void
    {
        $I->amOnUrl('http://127.0.0.1:8080/events/');

        $I->seeLink('Event 2', '/events?tx_cartevents_listevents%5Baction%5D=show&tx_cartevents_listevents%5Bevent%5D=2&cHash=c02777b6ed45d4187afc3fd7f1a42b85');
        $I->click('Event 2');

        $I->see('Event 2', 'h1');

        $I->see('Eventdate 2', '.event-event-date:nth-child(1) > h2');
        $I->see('31.07.2024 10:00 - 31.07.2024 12:00', '.event-event-date:nth-child(1) > div.date');
        $I->dontSee('Seats', '.event-event-date:nth-child(1)');
        $I->see('19,99 €', '.event-event-date:nth-child(1) span.regular-price > span.price');

        $I->dontSee('This event date can not be booked.');
        $I->seeElement("input[name='tx_cart_cart[quantity]']");
        $I->seeElement('input[type="submit"]');
    }

    #[Test]
    public function detailViewForBookableEventWithTwoOrMoreEventdatesWithoutPriceCategories(Tester $I): void
    {
        $I->amOnUrl('http://127.0.0.1:8080/events/');

        $I->seeLink('Event 3', '/events?tx_cartevents_listevents%5Baction%5D=show&tx_cartevents_listevents%5Bevent%5D=3&cHash=d1b28fe9400d030f4a10cd177e517670');
        $I->click('Event 3');

        $I->see('Event 3', 'h1');

        $I->see('Eventdate 3.1', '.event-event-date:nth-child(1) > h2');
        $I->see('31.07.2024 10:00 - 31.07.2024 12:00', '.event-event-date:nth-child(1) > div.date');
        $I->see('Seats: 9 / 10', '.event-event-date:nth-child(1) > div');
        $I->see('29,99 €', '.event-event-date:nth-child(1) span.regular-price > span.price');

        $I->see('Eventdate 3.2', '.event-event-date:nth-child(2) > h2');
        $I->see('15.08.2024 10:00 - 15.08.2024 11:00', '.event-event-date:nth-child(2) > div.date');
        $I->see('Seats: 8 / 10', '.event-event-date:nth-child(2) > div');
        $I->see('32,99 €', '.event-event-date:nth-child(2) span.regular-price > span.price');

        $I->see('Eventdate 3.3', '.event-event-date:nth-child(3) > h2');
        $I->see('16.08.2024 12:00 - 16.08.2024 13:30', '.event-event-date:nth-child(3) > div.date');
        $I->see('Seats: 7 / 10', '.event-event-date:nth-child(3) > div');
        $I->see('34,99 €', '.event-event-date:nth-child(3) span.regular-price > span.price');

        $I->dontSee('This event date can not be booked.');
    }
}
