<?php
require '../auth/access_restricted.php';
require '../templates/header.php';
require 'scripts/pagination.php';
require 'scripts/photos.php';
require 'scripts/modal.php';
?>

<div id="gallery">
	<form action='scripts/actions.php' method="GET" id="search">
		<input type="text" name="username" placeholder="Username" />
		<button type="submit">Search</button>
	</form>

	<?php
	if (!empty($photos)) {
		foreach ($photos as $photo) {
	?>
		<div class="cadre">
		<a href="gallery.php?id=<?= intval($photo['id']); ?>">
			<img class="miniature" id=<?= intval($photo['id']); ?> src=<?= htmlspecialchars($photo['content']); ?> />
		</a>
		<div class="actions">
			<?php if ($user->verifyMyPhoto($db, intval($photo['id']))) { ?>
				<img class='delete-mini mini-icon' src="<?= CAMAGRU_ROOT ?>/img/delete.png" alt="delete" />
			<?php }
			$action = ($user->getMyLike($db, intval($photo['id'])) == 0) ? 'like' : 'dislike'; ?>
			<a href="scripts/actions.php?actions=<?= htmlspecialchars($action); ?>&id=<?= intval($photo['id']); ?>">
				<?php
					$likeImg = ($action == 'dislike') ? CAMAGRU_ROOT.'/img/like_active.png' : CAMAGRU_ROOT.'/img/like_inactive.png';
					$active = ($action == 'dislike') ? 'active' : '';
				?>
				<img class="<?= $active; ?> like-mini mini-icon" src=<?= htmlspecialchars($likeImg); ?> alt="like"/>
			</a>
			<span class='nbLikes'><?= $user->getNbLikes($db, intval($photo['id']));?></span>
			<img class='comment-mini mini-icon' src="<?= CAMAGRU_ROOT ?>/img/comments.png" alt="comment" />
			<span class='nbComments'><?= intval($user->getNbComments($db, intval($photo['id']))); ?></span>
		</div>
		<p><?= htmlspecialchars($user->getUsername($db, intval($photo['user_id']))); ?></p>
		</div>
	<?php }
	}?>

	<div id="pagination">
	<?php
		$page = 0;
		while (++$page <= $nbPages)
		{
			if (intval($page) == $currentPage)
				echo "<p class='numSelected'>$page</p>";
			else
				echo "<p class='numbers'><a href='gallery.php?page=$page'>$page</a></p>";
		}
	?>
	</div>

</div>
<script type="text/javascript" src="<?= CAMAGRU_ROOT ?>/js/actions_btn.js"></script>
<script type="text/javascript" src="<?= CAMAGRU_ROOT ?>/js/modal.js"></script>

<?php require '../templates/footer.php'; ?>