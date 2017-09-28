<?php

namespace Adrium\Test;

use Aura\Auth\Adapter\AdapterInterface;
use Aura\Auth\Adapter\PdoAdapter;
use Aura\Auth\Exception\PasswordIncorrect;
use Aura\Auth\Service\LoginService;

class AuraAuthWorkaround
{
	public static function replace(LoginService $service, callable $verifyFn)
	{
		$adapter = Wrapper_AdapterReplacer::get($service);
		$adapter = new Wrapper_PdoAdapterVerifier($adapter, $verifyFn);
		Wrapper_AdapterReplacer::set($service, $adapter);
	}
}

/** @internal */
class Wrapper_AdapterReplacer
extends LoginService
{
	public static function get(LoginService $service)
	{
		return $service->adapter;
	}

	public static function set(LoginService $service, AdapterInterface $adapter)
	{
		$service->adapter = $adapter;
	}
}

/** @internal */
class Wrapper_PdoAdapterVerifier
extends PdoAdapter
{
	private $vfn;

	public function __construct(PdoAdapter $base, callable $vfn)
	{
		$this->pdo = $base->pdo;
		$this->cols = $base->cols;
		$this->from = $base->from;
		$this->where = $base->where;
		$this->cols[0] = 'externalkey';
		$this->cols[1] = 'NULL';
		$this->vfn = $vfn;
	}

	protected function fetchRow($input)
	{
		$data['username'] = $input['username'];
		return parent::fetchRow($data);
	}

	protected function verify($input, $data)
	{
		$vfn = $this->vfn;
		if (! $vfn($input, $data))
			throw new PasswordIncorrect;
	}
}
