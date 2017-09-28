<?php

use Adrium\Test\AuraAuthWorkaround;
use Adrium\Test\Services;
use Aura\Auth\Auth;
use Aura\Auth\Service\LoginService;
use Aura\Auth\Service\LogoutService;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$e = null;
$vfn = function ($input, $data) {
	return $data['username'] . $data['userid'] ===
		"test$input[str]";
};

try {
	$c = Services::getContainer(require 'config/config.php');

	$auth = $c->get(Auth::class);

	$service = $c->get(LoginService::class);

	if (isset($_POST['method'])) {
		if ($_POST['method'] === 'default') {
			$c->get(LoginService::class)->login($auth, [
				'username' => $_POST['username'],
				'password' => $_POST['password'],
			]);
		}

		if ($_POST['method'] === 'alt') {
			$service = $c->get(LoginService::class);
			AuraAuthWorkaround::replace($service, $vfn);
			$service->login($auth, [
				'username' => $_POST['username'],
				'password' => 'any',
				'str' => $_POST['password'],
			]);
		}

		if ($_POST['method'] === 'logout') {
			$c->get(LogoutService::class)->logout($auth);
		}
	}

	$loggedin = $auth->getUserName();
	$session = json_encode($_SESSION, 448);

} catch (Exception $e) {
	// OK
}

?>
Logged in: <?=$loggedin?>

<form method="POST">
	<input name="username" placeholder="Username"><br>
	<input name="password" placeholder="Password"><br>
	Login:
	<input type="submit" name="method" value="default">
	<input type="submit" name="method" value="alt">
	<input type="submit" name="method" value="logout">
</form>

<?php
	if ($session !== 'null')
		echo "<pre>Session: $session</pre>";

	if ($e !== null)
		echo "<pre>Exception: $e</pre>";
?>
