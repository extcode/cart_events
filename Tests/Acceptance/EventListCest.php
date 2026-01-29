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

use function PHPUnit\Framework\assertSame;

class EventListCest
{
    #[Test]
    public function listForEvents(Tester $I): void
    {
        $I->amOnUrl('http://127.0.0.1:8080/events/');

        $I->seeLink('Event 1', '/events?tx_cartevents_listevents%5Baction%5D=show&tx_cartevents_listevents%5Bcontroller%5D=Event&tx_cartevents_listevents%5Bevent%5D=1&cHash=b94e793b120e29763527f801db80844c');
        $I->see('Teaser 1');
        $I->seeLink('Event 2', '/events?tx_cartevents_listevents%5Baction%5D=show&tx_cartevents_listevents%5Bcontroller%5D=Event&tx_cartevents_listevents%5Bevent%5D=2&cHash=18eb8b460c56ca88173743ab54524f53');
        $I->see('Teaser 2');
        $I->seeLink('Event 3', '/events?tx_cartevents_listevents%5Baction%5D=show&tx_cartevents_listevents%5Bcontroller%5D=Event&tx_cartevents_listevents%5Bevent%5D=3&cHash=e30dcda6967105cf51f3d0ed454f4ba1');
        $I->see('Teaser 3');

        $I->dontSee('Event 4');
    }

    #[Test]
    public function detailViewForNonBookableEvent(Tester $I): void
    {
        $I->amOnUrl('http://127.0.0.1:8080/events/');

        $I->seeLink('Event 1', '/events?tx_cartevents_listevents%5Baction%5D=show&tx_cartevents_listevents%5Bcontroller%5D=Event&tx_cartevents_listevents%5Bevent%5D=1&cHash=b94e793b120e29763527f801db80844c');
        $I->click('Event 1');

        $I->see('Event 1', 'h1');
        $I->see('31.07.2024 10:00');
        $I->see('This event date can not be booked.');
    }

    #[Test]
    public function detailViewForBookableEventWithOneEventdateWithoutPriceCategories(Tester $I): void
    {
        $I->amOnUrl('http://127.0.0.1:8080/events/');

        $I->seeLink('Event 2', '/events?tx_cartevents_listevents%5Baction%5D=show&tx_cartevents_listevents%5Bcontroller%5D=Event&tx_cartevents_listevents%5Bevent%5D=2&cHash=18eb8b460c56ca88173743ab54524f53');
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

        $I->seeLink('Event 3', '/events?tx_cartevents_listevents%5Baction%5D=show&tx_cartevents_listevents%5Bcontroller%5D=Event&tx_cartevents_listevents%5Bevent%5D=3&cHash=e30dcda6967105cf51f3d0ed454f4ba1');
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

    #[Test]
    public function detailViewForBookableEventWithPriceCategories(Tester $I): void
    {
        $I->wantToTest('I can see a bookable event with price categories and all options are rendered correctly.');

        $I->amOnUrl('http://127.0.0.1:8080/events/');

        $I->seeLink('Event 5', '/events?tx_cartevents_listevents%5Baction%5D=show&tx_cartevents_listevents%5Bcontroller%5D=Event&tx_cartevents_listevents%5Bevent%5D=5&cHash=18ff1dbf4acad08e4f2f23af33a5c222');
        $I->click('Event 5');

        $I->see('Event 5', 'h1');

        $I->see('Eventdate 5', '.event-event-date:nth-child(1) > h2');
        $I->see('31.07.2024 10:00 - ', '.event-event-date:nth-child(1) > div.date');
        $I->see('Seats: 82 / 275', '.event-event-date:nth-child(1) > div');
        $I->see('15,00 €', '.event-event-date:nth-child(1) span.regular-price > span.price');

        $I->seeElement("select[name='tx_cart_cart[priceCategory]']");
        $I->dontSeeElement("select[name='tx_cart_cart[priceCategory]'] option[selected='selected']");

        $this->seeOption(
            $I,
            "select[name='tx_cart_cart[priceCategory]'] > option:nth-child(2)",
            'Category D',
            '4',
            '10,00 €'
        );
        $I->see('Category D', "select[name='tx_cart_cart[priceCategory]'] option[disabled='']");
        $this->seeOption(
            $I,
            "select[name='tx_cart_cart[priceCategory]'] > option:nth-child(3)",
            'Category C',
            '3',
            '15,00 €'
        );
        $this->seeOption(
            $I,
            "select[name='tx_cart_cart[priceCategory]'] > option:nth-child(4)",
            'Category B',
            '2',
            '17,00 €'
        );
        $this->seeOption(
            $I,
            "select[name='tx_cart_cart[priceCategory]'] > option:nth-child(5)",
            'Category A',
            '1',
            '22,00 €'
        );
    }

    #[Test]
    public function selectOptionForBookableEventWithPriceCategoriesChangePrice(Tester $I): void
    {
        $I->wantToTest('I can see a bookable event with price categories and all options are rendered correctly.');

        $I->amOnUrl('http://127.0.0.1:8080/events/');

        $I->seeLink('Event 5', '/events?tx_cartevents_listevents%5Baction%5D=show&tx_cartevents_listevents%5Bcontroller%5D=Event&tx_cartevents_listevents%5Bevent%5D=5&cHash=18ff1dbf4acad08e4f2f23af33a5c222');
        $I->click('Event 5');

        $I->see('15,00 €', '.event-event-date:nth-child(1) span.regular-price > span.price');

        $I->selectOption("select[name='tx_cart_cart[priceCategory]']", 'Category C');
        $I->see('15,00 €', '.event-event-date:nth-child(1) span.regular-price > span.price');

        $I->selectOption("select[name='tx_cart_cart[priceCategory]']", 'Category B');
        $I->see('17,00 €', '.event-event-date:nth-child(1) span.regular-price > span.price');

        $I->selectOption("select[name='tx_cart_cart[priceCategory]']", 'Category A');
        $I->see('22,00 €', '.event-event-date:nth-child(1) span.regular-price > span.price');

        $I->selectOption("select[name='tx_cart_cart[priceCategory]']", 'Category D');
        $I->dontSeeElement("select[name='tx_cart_cart[priceCategory]'] option[selected='selected']");
        $I->see('22,00 €', '.event-event-date:nth-child(1) span.regular-price > span.price');
    }

    private function seeOption(Tester $I, string $selector, string $label, string $value, string $price): void
    {
        $I->see($label, $selector);
        assertSame(
            $value,
            $I->grabValueFrom($selector)
        );
        assertSame(
            $price,
            $I->grabAttributeFrom($selector, 'data-regular-price')
        );
    }
}
