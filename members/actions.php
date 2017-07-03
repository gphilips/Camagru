<?php
$user = new User($_SESSION['auth']['id']);

$photos = $user->getPhotos($db);
?>