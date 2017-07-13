<?php
require '../auth/access_restricted.php';
require '../templates/header.php';

$user = new User($_SESSION['auth']['id']);

$nbPhotos = 12;
$nbPages = ceil($user->nbPhotoOfAllUsers($db) / $nbPhotos);

if (isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $nbPages)
	$currentPage = intval($_GET['page']);
else
	$currentPage = 1;
$start = ($currentPage - 1) * $nbPhotos;
$photos = $user->getPhotoOfAllUsers($db, $start, $nbPhotos);
?>

<div id="gallery">
	<?php
	if (!empty($photos)) {
		foreach ($photos as $photo) {
	?>
		<div class="cadre">
		<img class="miniature" id=<?= $photo['id']; ?> src=<?= $photo['content']; ?> />
		<div class="actions">
			<?php if ($user->verifyMyPhoto($db, $photo['id'])) { ?>
				<img class='delete-mini mini-icon' src="<?= CAMAGRU_ROOT ?>/img/delete.png" alt="delete" />
			<?php }
			$action = ($user->getMyLike($db, $photo['id']) == 0) ? 'like' : 'dislike'; ?>
			<a href="scripts/actions.php?actions=<?= $action; ?>&id=<?= $photo['id']; ?>">
				<?php
					$likeImg = ($action == 'dislike') ? '/camagru/img/like_active.png' : '/camagru/img/like_inactive.png';
					$active = ($action == 'dislike') ? 'active' : '';
				?>
				<img class='<?= $active; ?> like-mini mini-icon' src=<?= $likeImg; ?> alt="like"/>
			</a>
			<span class='nbLikes'><?= $user->getNbLikes($db, $photo['id']);?></span>
			<img class='comment-mini mini-icon' src="<?= CAMAGRU_ROOT ?>/img/comments.png" alt="comment" />
			<span class='nbComments'><?= $user->getNbComments($db, $photo['id']); ?></span>
		</div>
		<p><?= $user->getUsername($db, intval($photo['user_id'])); ?></p>
		</div>
	<?php }
	}?>

	<div id="pagination">
	<?php
		$page = 0;
		while (++$page <= $nbPages)
		{
			if ($page == $currentPage)
				echo "<p class='numSelected'>$page</p>";
			else
				echo "<p class='numbers'><a href='gallery.php?page=$page'>$page</a></p>";
		}
	?>
	</div>

</div>
<script type="text/javascript" src="<?= CAMAGRU_ROOT ?>/js/actions_btn.js"></script>
<?php require '../templates/footer.php'; ?>