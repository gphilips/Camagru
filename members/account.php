<?php 
require '../auth/access_restricted.php';
require '../templates/header.php';

$user = new User($_SESSION['auth']['id']);
$photos = $user->getPhotos($db);
?>

<div id="snap">
	<video id="webcam"></video>
	<canvas id="canvas"></canvas>
	<button id="take"><img src="../img/cam_icon.png"/></button>
</div>

<div id="sidebar-title">
	<h2><?php echo $_SESSION['auth']['username']; ?>'s photos</h2>
</div>
<div id="sidebar">
	<div id="mini-pictures">
	<?php
	if (!empty($photos)) {
		foreach ($photos as $photo) {
	?>
		<img class="miniature" id=<?= $photo['id']; ?> src=<?= $photo['content']; ?> />
		<div class="actions">
			<img class='delete-mini mini-icon' src="<?= CAMAGRU_ROOT ?>/img/delete.png" alt="delete" />
			<img class='like-mini mini-icon' src="<?= CAMAGRU_ROOT ?>/img/like_inactive.png" alt="like" />
			<span class='nbLikes'><?= $user->getNbLikes($db, $photo['id']);?></span>
			<img class='comment-mini mini-icon' src="<?= CAMAGRU_ROOT ?>/img/comments.png" alt="comment" />
			<span class='nbComments'><?= $user->getNbComments($db, $photo['id']); ?></span>
		</div>
	<?php }
	} else { ?>
		<p>Choose a filter and press the button to take a picture</p>
	<?php } ?>
	</div>
</div>

<script type="text/javascript" src="../js/webcam.js"></script>
<?php require '../templates/footer.php'; ?>