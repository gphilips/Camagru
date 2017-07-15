<?php
$user = new User($_SESSION['auth']['id']);

$nbPhotos = 12;
$nbPages = ceil($user->nbPhotoOfAllUsers($db) / $nbPhotos);

if (isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $nbPages)
	$currentPage = intval($_GET['page']);
else
	$currentPage = 1;
$start = ($currentPage - 1) * $nbPhotos;
?>