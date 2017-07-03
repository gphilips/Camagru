<?php
require '../templates/autoload.php';
require_once '../config/setup.php';

$session = Session::getInstance();
$db = App::getDatabase($pdo);

$user = new User($_SESSION['auth']['id']);

if (isset($_POST['imageTaken']))
	$user->insertPhoto($db, $_POST['imageTaken']);

App::redirect('../account.php');
?>