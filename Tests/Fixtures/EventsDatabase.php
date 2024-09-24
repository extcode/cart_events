<?php

declare(strict_types=1);

return [
    'tx_cartevents_domain_model_event' => [
        0 => [
            'uid' => '1',
            'pid' => '7',
            'sku' => 'event-1',
            'title' => 'Event 1',
            'teaser' => 'Teaser 1',
            'description' => '',
            'meta_description' => '',
            'audience' => '',
            'path_segment' => 'event-1',
        ],
        1 => [
            'uid' => '2',
            'pid' => '7',
            'sku' => 'event-2',
            'title' => 'Event 2',
            'teaser' => 'Teaser 2',
            'description' => '',
            'meta_description' => '',
            'audience' => '',
            'path_segment' => 'event-2',
        ],
        2 => [
            'uid' => '3',
            'pid' => '7',
            'sku' => 'event-3',
            'title' => 'Event 3',
            'teaser' => 'Teaser 3',
            'description' => '',
            'meta_description' => '',
            'audience' => '',
            'path_segment' => 'event-3',
        ],
        3 => [
            'uid' => '4',
            'pid' => '9',
            'sku' => 'event-4',
            'title' => 'Event 4',
            'teaser' => '',
            'description' => '',
            'meta_description' => '',
            'audience' => '',
            'path_segment' => 'event-4',
        ],
    ],
    'tx_cartevents_domain_model_eventdate' => [
        0 => [
            'uid' => '1',
            'pid' => '7',
            'event' => '1',
            'sku' => 'eventdate-1',
            'title' => 'Eventdate 1',
            'begin' => '1722420000',
            'location' => '',
            'lecturer' => '',
            'note' => '',
            'price' => 9.99,
            'bookable' => false,
        ],
        1 => [
            'uid' => '2',
            'pid' => '7',
            'event' => '2',
            'sku' => 'eventdate-2',
            'title' => 'Eventdate 2',
            'begin' => '1722420000',
            'end' => '1722427200',
            'location' => '',
            'lecturer' => '',
            'note' => '',
            'price' => 19.99,
            'bookable' => true,
        ],
        2 => [
            'uid' => '3',
            'pid' => '7',
            'event' => '3',
            'sku' => 'eventdate-3-1',
            'title' => 'Eventdate 3.1',
            'begin' => '1722420000',
            'end' => '1722427200',
            'location' => 'Berlin',
            'lecturer' => 'Max Mustermann',
            'note' => '',
            'price' => 29.99,
            'bookable' => true,
        ],
        3 => [
            'uid' => '4',
            'pid' => '7',
            'event' => '3',
            'sku' => 'eventdate-3-2',
            'title' => 'Eventdate 3.2',
            'begin' => '1723716000',
            'end' => '1723719600',
            'location' => 'Hamburg',
            'lecturer' => 'Erika Musterfrau',
            'note' => '',
            'price' => 32.99,
            'bookable' => true,
        ],
        4 => [
            'uid' => '5',
            'pid' => '7',
            'event' => '3',
            'sku' => 'eventdate-3-3',
            'title' => 'Eventdate 3.3',
            'begin' => '1723809600',
            'end' => '1723815000',
            'location' => 'München',
            'lecturer' => 'Erika Musterfrau',
            'note' => '',
            'price' => 34.99,
            'bookable' => true,
        ],
        5 => [
            'uid' => '6',
            'pid' => '9',
            'event' => '4',
            'sku' => 'eventdate-4',
            'title' => 'Eventdate 4',
            'begin' => '1722420000',
            'location' => '',
            'lecturer' => '',
            'note' => '',
            'price' => 9.99,
            'bookable' => true,
        ],
    ],
];