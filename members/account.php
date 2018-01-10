<?php 
require '../auth/access_restricted.php';
require '../templates/header.php';

$user = new User($_SESSION['auth']['id']);
$photos = $user->getMyPhotos($db);
?>

<div id="snap">
	<video id="webcam"></video>
	<canvas id="canvas"></canvas>
	<button id="take" disabled="true"><img src="<?= CAMAGRU_ROOT ?>/img/cam_icon.png"/></button>
</div>
<div id="object">
	<ul>
		<li class="imagePng"><img id ='snapback' src="<?= CAMAGRU_ROOT ?>/img/snapback.png" alt='snapback'></li>
		<li class="imagePng"><img id ='gangsta' src="<?= CAMAGRU_ROOT ?>/img/gangsta.png" alt='gangsta'></li>
		<li class="imagePng"><img id ='lol' src="<?= CAMAGRU_ROOT ?>/img/lol.png" alt='lol'></li>
		<li class="imagePng"><img id ='batman' src="<?= CAMAGRU_ROOT ?>/img/batman.png" alt='batman'></li>
		<li class="imagePng"><img id ='boss' src="<?= CAMAGRU_ROOT ?>/img/boss.png" alt='boss'></li>
		<li class="imagePng"><img id ='chain' src="<?= CAMAGRU_ROOT ?>/img/chain.png" alt='chain'></li>
	</ul>
</div>

<div id="sidebar-title">
	<h2><?= htmlspecialchars($user->getUsername($db, $_SESSION['auth']['id'])); ?>'s photos</h2>
</div>
<div id="sidebar">
	<div id="mini-pictures">
	<?php
	if (!empty($photos)) {
		foreach ($photos as $photo) {
	?>
		<img class="miniature" id=<?= intval($photo['id']); ?> src=<?= htmlspecialchars($photo['content']); ?> />
		<div class="actions">
			<?php if ($user->verifyMyPhoto($db, intval($photo['id']))) { ?>
				<img class='delete-mini mini-icon' src="<?= CAMAGRU_ROOT ?>/img/delete.png" alt="delete" />
			<?php } ?>
			<?php
				$action = ($user->getMyLike($db, $photo['id']) == 0) ? 'like' : 'dislike'; ?>
				<a href="scripts/actions.php?actions=<?= htmlspecialchars($action); ?>&id=<?= intval($photo['id']); ?>">
				<?php
					$likeImg = ($action == 'dislike') ? CAMAGRU_ROOT.'/img/like_active.png' : CAMAGRU_ROOT.'/img/like_inactive.png';
					$active = ($action == 'dislike') ? 'active' : '';
				?>
				<img class="<?= $active; ?> like-mini mini-icon" src=<?= htmlspecialchars($likeImg); ?> alt="like"/>
				</a>
			<span class='nbLikes'><?= intval($user->getNbLikes($db, $photo['id']));?></span>
			<img class='comment-mini mini-icon' src="<?= CAMAGRU_ROOT ?>/img/comments.png" alt="comment" />
			<span class='nbComments'><?= intval($user->getNbComments($db, $photo['id'])); ?></span>
		</div>
	<?php }
	} else { ?>
		<p>Choose a filter and press the button to take a picture</p>
	<?php } ?>
	</div>
</div>

<script type="text/javascript" src="<?= CAMAGRU_ROOT ?>/js/webcam.js"></script>
<script type="text/javascript" src="<?= CAMAGRU_ROOT ?>/js/actions_btn.js"></script>
<?php require '../templates/footer.php'; ?>