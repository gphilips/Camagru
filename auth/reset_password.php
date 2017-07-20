<?php
require '../templates/autoload.php';
require_once '../config/setup.php';

if (isset($_GET['id']) && isset($_GET['token']))
{
	$db = App::getDatabase($pdo);
	$session = Session::getInstance();
	$auth = new Auth($session);

	$user = $auth->TokenGetUser($db, intval($_GET['id']), htmlspecialchars($_GET['token']));
	if ($user)
	{
		if (!empty($_POST))
		{
			$validate = new Validate($_POST);
			$validate->isConfirmed('pwd', "You didn't set your password two times");
			if ($validate->isValid())
			{
				$password = hash('whirlpool', $_POST['pwd']);
				$db->query("UPDATE users SET password = ?, reset_at = NULL, reset_token = NULL WHERE id = ?", [$password, intval($_GET['id'])]);

				$session->setFlash('success', "Your password has been changed");
				$auth->connect($user);

				App::redirect('../index.php');
			}
		}
	}
	else
	{
		$session->setFlash('danger', "This token is incorrect");
		App::redirect('../index.php');
	}
}
else
{
	Session::getInstance()->setFlash('dangersNav', "You don't have the right to access this file");
	App::redirect('../index.php');
}

?>