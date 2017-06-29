<?php
require './templates/autoload.php';
require_once './config/setup.php';

$session = Session::getInstance();

$auth = new Auth($session);

$db = App::getDatabase($pdo);

$auth->cookieAutoConnect($db);

if ($auth->isConnected())
	App::redirect('./account.php');

if (!empty($_POST) && !empty($_POST['username']) && !empty($_POST['pwd']))
{
	$user = $auth->login($db, $_POST['username'], $_POST['pwd'], isset($_POST['remember']));

	if ($user)
		App::redirect('./account.php');
	else
	{
		$session->setFlash('danger', "Username/Email or password is incorrect");
		App::redirect('./index.php');
	}

}
?>