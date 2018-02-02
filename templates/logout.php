<?php
require 'autoload.php';

$session = Session::getInstance();
$auth = new Auth($session);

if (isset($_COOKIE['remember']))
{
	unset($_COOKIE['remember']);
	setcookie('remember', '', time()-3600, '/');
}

$session->delete_session('auth');

$session->setFlash('success', "You are now logged out");

App::redirect('/camagru/index.php');
?>