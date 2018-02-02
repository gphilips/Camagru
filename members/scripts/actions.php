<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_GET['username']) && !isset($_GET['actions']) && !isset($_GET['id']) && !isset($_GET['receiveMail']))
{
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    header('Location: /camagru/index.php');	
}

require '../../templates/autoload.php';
require_once '../../config/setup.php';
//require 'mergeImage.php';

$session = Session::getInstance();
$db = App::getDatabase($pdo);
$auth = new Auth($session);

$getId = (isset($_SESSION['auth']['id'])) ? $_SESSION['auth']['id'] : $_SESSION['no-auth']['id'];
$user = new User($getId);

if ($_POST)
{
	if (isset($_POST['imageTaken']) && !empty($_POST['imageTaken']))
	{
		//$fusion = mergeImage($_POST['imageTaken'], );
		$user->setPhoto($db, htmlspecialchars($fusion));
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
		if (isset($_SESSION['auth']))
		{
			$user->alertComment($db, intval($_POST['send']), htmlspecialchars($_POST['content']));
			$user->setComment($db, htmlspecialchars($_POST['content']), intval($_POST['send']));
			$session->setFlash('successModal', 'Your comment has been successfully sent.');
			App::redirect($_SERVER['HTTP_REFERER']);
		}
		else
		{
			$session->setFlash('danger', 'Sorry, but you have to create an account.');
			App::redirect('../../register.php');
		}
	}
	else if (isset($_POST['commentDelete']) && !empty($_POST['commentDelete']) && is_numeric($_POST['commentDelete']))
	{
		$user->delete($db, 'comments', intval($_POST['commentDelete']));
		$session->setFlash('successModal', 'Your comment has been successfully removed.');
		App::redirect($_SERVER['HTTP_REFERER']);
	}
	else if (isset($_POST['username']))
	{
		$validate = new Validate($_POST);

		$validate->isAlphanum('username', "Your username is not valid (8 alphanumeric characters minimum)");
		$validate->isUnique('username', $db, 'users', "Your username is already taken");
		if ($validate->isValid())
		{
			$success = $auth->changeField($db, $_SESSION['auth']['id'], 'username', $_POST['username']);
			if ($success)
				$session->setFlash('success', "Your username has been changed");
		}
		else
			$session->setFlash('danger', implode("\n", $validate->getErrors()));
		App::redirect($_SERVER['HTTP_REFERER']);
	}
	else if (isset($_POST['resetPwd']))
	{
		$user = $auth->resetPassword($db, $_SESSION['auth']['email']);
		$session->setFlash('success', "We sent you an email to reset your password");
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
		if (isset($_SESSION['auth']))
		{		
			if ($_GET['actions'] == 'like')
				$user->setLike($db, $_GET['id']);
			else if ($_GET['actions'] == 'dislike')
				$user->deleteLike($db, $_GET['id']);

			App::redirect($_SERVER['HTTP_REFERER']);
		}
		else
		{
			$session->setFlash('danger', 'Sorry, but you have to create an account.');
			App::redirect('../../register.php');
		}
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
				$session->setFlash('dangersNav', "Sorry, there is no photos of $name yet");
			App::redirect("../gallery.php?search=$user_id");
		}
		else
		{
			$session->setFlash('dangersNav', 'Sorry, this username does not tell us anything.');
			App::redirect('../gallery.php');
		}

	}
	else if (isset($_GET['receiveMail']))
	{
		$onOff = (htmlspecialchars($_GET['receiveMail']) == 'On') ? 1 : 0;
		$success = $auth->changeField($db, $_SESSION['auth']['id'], 'receiveMail', $onOff);
		if ($success != NULL)
			$session->setFlash('success', "Your change has been made");
		else
			$session->setFlash('danger', "Sorry, but your change hasn't been made. Try again");
		App::redirect($_SERVER['HTTP_REFERER']);
	}
	else
		App::redirect('../gallery.php');
}

?>