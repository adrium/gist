<?php

namespace Adrium\Test;

use ArrayObject;
use Aura\Auth\Adapter\AdapterInterface;
use Aura\Auth\Auth;
use Aura\Auth\AuthFactory;
use Aura\Auth\Service\LoginService;
use Aura\Auth\Service\LogoutService;
use PDO;
use Xtreamwayz\Pimple\Container;

class Services
{
	public function getContainer(array $config)
	{
		$result = new Container;

		$result['config'] = new ArrayObject($config);

		$result[PDO::class] = function ($c) {
			$config = $c->get('config')['db'];
			$result = new PDO($config['dsn'], $config['user'], $config['pass']);
			$result->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $result;
		};

		$result[AdapterInterface::class] = function ($c) {
			$pdo = $c->get(PDO::class);
			$config = $c->get('config')['auth'];
			return $c->get(AuthFactory::class)->newPdoAdapter(
				$pdo,
				$config['verifier'],
				$config['cols'],
				$config['from'],
				$config['where']
			);
		};

		$result[AuthFactory::class] = function () {
			return new AuthFactory([]);
		};

		$result[Auth::class] = function ($c) {
			return $c->get(AuthFactory::class)->newInstance();
		};

		$result[LoginService::class] = function ($c) {
			$adapter = $c->get(AdapterInterface::class);
			return $c->get(AuthFactory::class)->newLoginService($adapter);
		};

		$result[LogoutService::class] = function ($c) {
			$adapter = $c->get(AdapterInterface::class);
			return $c->get(AuthFactory::class)->newLogoutService($adapter);
		};

		return $result;
	}
}
