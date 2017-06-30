<?php
require 'templates/autoload.php';
require_once 'config/setup.php';

$session = Session::getInstance();

$auth = new Auth($session);

$db = App::getDatabase($pdo);

$auth->cookieAutoConnect($db);

if ($auth->isConnected())
 	App::redirect('account.php');

?>