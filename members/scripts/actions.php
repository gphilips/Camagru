<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_GET['username']) && !isset($_GET['actions']) && !isset($_GET['id']))
{
	header('HTTP/1.0 403 Forbidden', TRUE, 403);
	Session::getInstance()->setFlash('dangersNav', "You don't have the right to access this file");
    App::redirect('/camagru/index.php');	
}

require '../../templates/autoload.php';
require_once '../../config/setup.php';

$session = Session::getInstance();
$db = App::getDatabase($pdo);

$user = new User($_SESSION['auth']['id']);

if ($_POST)
{
	if (isset($_POST['imageTaken']) && !empty($_POST['imageTaken']))
	{
		$user->setPhoto($db, htmlspecialchars($_POST['imageTaken']));
		$session->setFlash('successNav', 'Your picture has been successfully added.');
		App::redirect($_SERVER['HTTP_REFERER']);
	}
	else if (isset($_POST['imageDelete']) && !empty($_POST['imageDelete']) && is_numeric($_POST['imageDelete']))
	{
		$user->delete($db, 'photos', intval($_POST['imageDelete']));
		$session->setFlash('successNav', 'Your picture has been successfully removed.');
		App::redirect($_SERVER['HTTP_REFERER']);
	}
	else if (isset($_POST['content']) && !empty($_POST['content']) && isset($_POST['send']) && !empty($_POST['send']) && is_numeric($_POST['send']))
	{
		$user->alertComment($db, intval($_POST['send']), htmlspecialchars($_POST['content']));
		$user->setComment($db, htmlspecialchars($_POST['content']), intval($_POST['send']));
		$session->setFlash('successModal', 'Your comment has been successfully sent.');
		App::redirect($_SERVER['HTTP_REFERER']);
	}
	else if (isset($_POST['commentDelete']) && !empty($_POST['commentDelete']) && is_numeric($_POST['commentDelete']))
	{
		$user->delete($db, 'comments', intval($_POST['commentDelete']));
		$session->setFlash('successModal', 'Your comment has been successfully removed.');
		App::redirect($_SERVER['HTTP_REFERER']);
	}
	else
	{
		$session->setFlash('dangerNav', 'Sorry, your request has failed.');
		if ($_SERVER['HTTP_REFERER'] == 'gallery.php')
			App::redirect($_SERVER['HTTP_REFERER']);
		else
			App::redirect('../account.php');
	}
}
else if ($_GET)
{
	if (isset($_GET['actions']) && !empty($_GET['actions']) && isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']))
	{
		if ($_GET['actions'] == 'like')
			$user->setLike($db, $_GET['id']);
		else if ($_GET['actions'] == 'dislike')
			$user->deleteLike($db, $_GET['id']);

		App::redirect($_SERVER['HTTP_REFERER']);
	}
	else if (isset($_GET['username']) && !empty($_GET['username']))
	{
		$validate = new Validate($_GET);
		if ($user_id = $validate->isUnique('username', $db, 'users'))
		{
			$name = $user->getUsername($db, $user_id);
			$nbPhoto = $user->nbPhotoOfUser($db, $user_id);
			if ($nbPhoto > 0)
				$session->setFlash('successNav', "We found $name"."'s photos");
			else
				$session->setFlash('dangersNav', "Sorry, there is no photos yet of $name");
			App::redirect("../gallery.php?search=$user_id");
		}
		else
		{
			$session->setFlash('dangersNav', 'Sorry, this username does not tell us anything.');
			App::redirect('../gallery.php');
		}

	}
	else
		App::redirect('../gallery.php');
}

?>