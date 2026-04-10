<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Scramble: Laravel API documentation
    |--------------------------------------------------------------------------
    */

    'title' => 'BookRegister API',

    'servers' => [],

    'security' => [
        'sanctumApi' => [
            'type' => 'http',
            'scheme' => 'bearer',
            'bearer_format' => 'token',
            'description' => 'Sanctum API Token',
        ],
    ],

    'components' => [
        'securitySchemes' => [
            'sanctumApi' => [
                'type' => 'http',
                'scheme' => 'bearer',
                'bearerFormat' => 'token',
                'description' => 'Sanctum API Token',
            ],
        ],
    ],

    'info' => [
        'description' => 'API для управления книгами',
    ],

    'tags' => [
        [
            'name' => 'Authentication',
            'description' => 'Endpoints для авторизации',
        ],
    ],

    'ui' => 'swagger',

];
