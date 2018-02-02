<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))
{
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
	header('Location: /camagru/index.php');
}

require 'templates/autoload.php';
require_once 'config/setup.php';

$session = Session::getInstance();

$auth = new Auth($session);

$db = App::getDatabase($pdo);

$auth->cookieAutoConnect($db);

if (!$auth->isConnected($db))
{
	if (isset($_COOKIE['remember']))
	{
		unset($_COOKIE['remember']);
		setcookie('remember', '', time()-3600, '/');
	}

	$session->delete_session('auth');
}
else
	App::redirect('members/account.php');

?>