<?php
require '../../templates/autoload.php';
require_once '../../config/setup.php';

$session = Session::getInstance();
$db = App::getDatabase($pdo);

$user = new User($_SESSION['auth']['id']);

if ($_POST)
{
	if (isset($_POST['imageTaken']) && !empty($_POST['imageTaken']))
	{
		$user->setPhoto($db, $_POST['imageTaken']);
		$session->setFlash('successNav', 'Your picture has been successfully added.');
	}
	else if (isset($_POST['imageDelete']) && !empty($_POST['imageDelete']) && is_numeric($_POST['imageDelete']))
	{
		$user->delete($db, 'photos', $_POST['imageDelete']);
		$session->setFlash('successNav', 'Your picture has been successfully removed.');
	}
	else
		$session->setFlash('dangerNav', 'Sorry, your request has failed.');

	if ($_SERVER['HTTP_REFERER'] == 'http://localhost:8888/camagru/members/gallery.php')
		App::redirect('../gallery.php');
	elseif ($_SERVER['HTTP_REFERER'] == 'http://localhost:8888/camagru/members/account.php')
		App::redirect('../account.php');
	else
		App::redirect('../account.php');
}
else if ($_GET)
{
	if (isset($_GET['actions']) && !empty($_GET['actions']) && isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']))
	{
		if ($_GET['actions'] == 'like')
			$user->setLike($db, $_GET['id']);
		else if ($_GET['actions'] == 'dislike')
			$user->deleteLike($db, $_GET['id']);

		App::redirect('../gallery.php');
	}
	else
		App::redirect('../gallery.php');
}

?>