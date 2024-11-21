<?php
// 数据库配置
return [
    'default' => [
        'type' => 'mysqli',
        'host' => 'localhost',
        'port' => 3306,
        'database' => 'app',
        'username' => 'root',
        'password' => 'root',
        'tablepre' => 'db_',
        'charset' => 'utf8',
        'debug' => true,
        'pconnect' => 0,
        'autoconnect' => 0
    ],
    'access' => [
        'type' => 'access',
        'database' => 'path/to/your/database.accdb',
        'username' => '',
        'password' => '',
        'tablepre' => 'db_',
        'debug' => true,
    ],
    'sqlite' => [
        'type' => 'sqlite',
        'database' => 'path/to/your/database.sqlite',
        'tablepre' => 'db_',
        'debug' => true,
    ],
];
