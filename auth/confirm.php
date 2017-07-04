<?php
require '/camagru/templates/autoload.php';
require_once '/camagru/config/setup.php';

$db = App::getDatabase($pdo);

$session = Session::getInstance();
$auth = new Auth($session);

if ($auth->confirm($db, $_GET['id'], $_GET['token'], $session))
{
	$session->setFlash('success', "Your account has been successfully confirmed !");
	App::redirect('/camagru/account.php');
}
else
{
	$session->setFlash('danger', "This token is no longer available");
	App::redirect('/camagru/index.php');
}
?>