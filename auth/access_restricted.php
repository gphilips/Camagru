<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))
{
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    header('Location: /camagru/index.php');
}
    
require '../templates/autoload.php';
require_once '../config/setup.php';

$db = App::getDatabase($pdo);
$session = Session::getInstance();
$auth = new Auth($session);

if (isset($_POST['username']) && isset($_POST['pwd'])
	&& !empty($_POST) && !empty($_POST['username']) && !empty($_POST['pwd']))
{
	if (!isset($_POST['remember']))
		$_POST['remember'] = false;

	$user = $auth->login($db, htmlspecialchars($_POST['username']), htmlspecialchars($_POST['pwd']), htmlspecialchars($_POST['remember']));
	if (!$user)
	{
		$session->setFlash('danger', "Username/Email or password is incorrect");
		App::redirect('/camagru/index.php');
	}
	
	if (!$user['confirm_at'] || $user['confirm_token'])
			$auth->restrict();
}
else
	$auth->restrict();

?>