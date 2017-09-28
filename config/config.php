<?php return [
	'db' => [
		'dsn' => 'mysql:dbname=test',
		'user' => 'test',
		'pass' => null,
	],
	'auth' => [
		'verifier' => PASSWORD_DEFAULT,
		'cols' => [
			'username',
			'password',
			'userid',
		],
		'from' => 'users',
		'where' => null,
	],
];
