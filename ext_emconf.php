<?php

$EM_CONF['cart_events'] = [
    'title' => 'Cart - Events',
    'description' => 'Shopping Cart(s) for TYPO3 - Event Extension',
    'category' => 'plugin',
    'version' => '7.0.0',
    'state' => 'stable',
    'author' => 'Daniel Gohlke',
    'author_email' => 'ext@extco.de',
    'author_company' => 'extco.de UG (haftungsbeschränkt)',
    'constraints' => [
        'depends' => [
            'typo3' => '14.1.0-14.4.99',
            'cart' => '12.0.0',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
