<?php

$EM_CONF['cart_events'] = [
    'title' => 'Cart - Events',
    'description' => 'Shopping Cart(s) for TYPO3 - Event Extension',
    'category' => 'plugin',
    'version' => '5.0.0',
    'state' => 'stable',
    'author' => 'Daniel Gohlke',
    'author_email' => 'ext@extco.de',
    'author_company' => 'extco.de UG (haftungsbeschränkt)',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-12.4.99',
            'cart' => '10.0.0',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
