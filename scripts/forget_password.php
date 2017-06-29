<?php
require './templates/autoload.php';
require_once './config/setup.php';

if (!empty($_POST) && !empty($_POST['email'])) {
	
	$db = App::getDatabase();
	$session = Session::getInstance();
	$auth = new Auth($session);

	if ($auth->resetPassword($db, $_POST[email]))
	{
		$session->setFlash('success', "We sent you an email to reset your password");
		App::redirect('./index.php');
	}
	else
	{
		$session->setFlash('danger', "Your email is unknown to us");
		App::redirect('./index.php');
	}
}
?>