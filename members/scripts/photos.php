<?php
$user = new User($_SESSION['auth']['id']);
	
$modal = false;
$style = '';
if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0)
{
	$photo_id = $_GET['id'];
	$modal = true;
}

if (isset($_GET['search']) && is_numeric($_GET['search']) && $_GET['search'] > 0)
	$photos = $user->getPhotoOfUser($db, $start, $nbPhotos, $_GET['search']);
else
	$photos = $user->getPhotoOfAllUsers($db, $start, $nbPhotos);
?>