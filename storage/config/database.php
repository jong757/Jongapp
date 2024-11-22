<?php
// 数据库配置
return [
    'mysqli' => [
        'default' => [
			'host' => 'localhost',
			'port' => 3306,
			'database' => 'app',
			'username' => 'root',
			'password' => 'root',
			'tablepre' => 'db_',
			'charset' => 'utf8',
			'debug' => true,
		],
    ],
    'access' => [
		'default' => [
			'database' => 'path/to/your/database.accdb',
			'username' => '',
			'password' => '',
			'tablepre' => 'db_',
			'debug' => true,
		],

    ],
    'sqlite' => [
        'default' => [
			'database' => 'path/to/your/database.sqlite',
			'tablepre' => 'db_',
			'debug' => true,
		],

    ],
];
