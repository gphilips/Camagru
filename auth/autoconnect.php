<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))
{
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
	header('Location: /camagru/index.php');
}

require 'templates/autoload.php';
require_once 'config/setup.php';

$session = Session::getInstance();

$auth = new Auth($session);

$db = App::getDatabase($pdo);

$auth->cookieAutoConnect($db);

if ($auth->isConnected())
 	App::redirect('members/account.php');

?>