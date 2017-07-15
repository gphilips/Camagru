<?php
require '../templates/autoload.php';
require_once '../config/setup.php';

$db = App::getDatabase($pdo);

$session = Session::getInstance();
$auth = new Auth($session);

if ($auth->confirm($db, intval($_GET['id']), htmlspecialchars($_GET['token']), $session))
{
	$session->setFlash('success', "Your account has been successfully confirmed !");
	App::redirect('/camagru/members/account.php');
}
else
{
	$session->setFlash('danger', "This token is no longer available");
	App::redirect('/camagru/index.php');
}
?>