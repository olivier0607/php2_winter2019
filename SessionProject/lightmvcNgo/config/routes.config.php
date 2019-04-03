<?php

$baseConfig['routes'] = [
    0 => [
        'GET',
        '/',
        'index',
    ],
    1 => [
        'GET',
        '/index[/{action}]',
        'index',
    ],
    2 => [
        ['GET', 'POST'],
        '/products[/{action}[/{id:[0-9]+}]]',
        'product',
    ],
    3 => [
        'GET',
        '/baz[/{action}]',
        'specialmodule/index',
    ],
    4 => [
        'GET',
        '/admin[/{action}]',
        'specialmodule/index',
    ],

    5 => [
        ['GET', 'POST'],
        '/products2[/{action}[/{id:[0-9]+}]]',
        'product2',
    ],
    6 => [
        ['GET', 'POST'],
        '/users[/{action}[/{id:[0-9]+}]]',
        'loggin',
    ],


];