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
}


App::redirect('../account.php');
?>