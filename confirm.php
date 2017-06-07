<?php

require_once 'config/setup.php';

$user_id = $_GET['id'];
$user_token = $_GET['token'];


$req = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$req->execute([$user_id]);
$user = $req->fetch();

if ($user && $user['confirm_token'] === $user_token) {
	session_start();

	$req = $pdo->prepare("UPDATE users SET confirm_token = NULL, confirm_at = NOW() WHERE id = ?");
	$req->execute([$user_id]);
	$_SESSION['auth'] = $user;

	$_SESSION['flash']['success'] = "Your account has been successfully confirmed ! You can log in now.";
	header('Location: index.php');
}
else {
	$_SESSION['flash']['danger'] = "This token is no longer available";
	header('Location: index.php');
}