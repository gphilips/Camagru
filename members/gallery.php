<?php
require '../auth/access_restricted.php';
require '../templates/header.php';

$user = new User($_SESSION['auth']['id']);
$photos = $user->getPhotoOfAllUsers($db);
?>

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
	} ?>
</div>

<?php require '../templates/footer.php'; ?>