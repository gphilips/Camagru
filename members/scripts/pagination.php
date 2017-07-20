<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']) && !isset($_GET['page']))
{
	header('HTTP/1.0 403 Forbidden', TRUE, 403);
    header('Location: /camagru/index.php');	
}

$user = new User($_SESSION['auth']['id']);

$nbPhotos = 12;
$nbPages = ceil($user->nbPhotoOfAllUsers($db) / $nbPhotos);

if (isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $nbPages)
	$currentPage = intval($_GET['page']);
else
	$currentPage = 1;
$start = ($currentPage - 1) * $nbPhotos;
?>