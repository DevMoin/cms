<?php

$tables['user'] = [
    'fields' => [
        'uid' => [
            'type' => "INT",
            'null' => false,
            'extra' => 'AUTO_INCREMENT',
        ],
        'username' => [
            'type' => 'VARCHAR',
            'length' => 60,
            'null' => false,
        ],
        'email' => [
            'type' => 'VARCHAR',
            'length' => 60,
            'null' => false,
        ],
        'password' => [
            'type' => 'VARCHAR',
            'length' => 60,
            'null' => false,
        ],

    ],
    'config' => [
        'primary_key' => 'uid',
    ],
    'data' => [
        [null, 'admin', 'admin@admin.com', 'admin']
    ]
];
