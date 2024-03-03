<?php

$tables['config'] = [
    'fields' => [
        'name' => [
            'type' => 'VARCHAR',
            'length' => 60,
            'null' => false,
        ],
        'value' => [
            'type' => 'LONGBLOB',
            'null' => false,
        ]
    ],
    'config' => [
        'primary_key' => 'name',
    ],
    // 'data' => [
    //     ['kotak', 'ghin']
    // ]
];
