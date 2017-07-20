<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))
{
	header('HTTP/1.0 403 Forbidden', TRUE, 403);
    header('Location: /camagru/index.php');	
}

require 'templates/autoload.php';
require_once 'config/setup.php';

if (!empty($_POST) && isset($_POST['email']) && !empty($_POST['email'])) {
	
	$db = App::getDatabase($pdo);
	$session = Session::getInstance();
	$auth = new Auth($session);

	if ($auth->resetPassword($db, htmlspecialchars($_POST['email'])))
	{
		$session->setFlash('success', "We sent you an email to reset your password");
		App::redirect('index.php');
	}
	else
	{
		$session->setFlash('danger', "Your email is unknown to us");
		App::redirect('forget.php');
	}
}
?>