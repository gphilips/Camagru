<?php
require './templates/autoload.php';
require_once './config/setup.php';

$db = App::getDatabase($pdo);
$session = Session::getInstance();
$auth = new Auth($session);

if (isset($_POST['username']) && isset($_POST['pwd']) 
	&& !empty($_POST) && !empty($_POST['username']) && !empty($_POST['pwd']))
{
	$user = $auth->login($db, $_POST['username'], $_POST['pwd']);
	if (!$user)
	{
		$session->setFlash('danger', "Username/Email or password is incorrect");
		App::redirect('./index.php');
	}
	
	if (!$user['confirm_at'] || $user['confirm_token'])
			$auth->restrict();	
}
else
	$auth->restrict();

?>