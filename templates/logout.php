<?php
require 'autoload.php';

$session = Session::getInstance();

setcookie('remember', NULL, -1);
$session->delete_session('auth');

$session->setFlash('success', "You are now logged out");

App::redirect('../index.php');
?>