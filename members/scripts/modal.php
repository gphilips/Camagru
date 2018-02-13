<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))
{
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    header('Location: /camagru/index.php');
}

if (isset($modal) && $modal) {
	$user = new User($_SESSION['auth']['id']);
	$picture = $user->getPhoto($db, $photo_id);
?>
<div id="overlay">
	<div id="modal">
		<img id=<?= intval($picture['id']); ?> src="<?= CAMAGRU_ROOT ?>/img/photos/<?= htmlspecialchars($picture['content']); ?>">
		<div id="info">
			<div id="head">
				<p id="author"><strong><?= htmlspecialchars($user->getUsername($db, $picture['user_id'])); ?></strong></p>
				<p class='date'><?= htmlspecialchars(date('M jS Y, H:i', strtotime($picture['created_at'])));  ?></p>
			</div>

			<div id='act'>
				<?php
				$action = ($user->getMyLike($db, $picture['id']) == 0) ? 'like' : 'dislike'; ?>
				<a href="scripts/actions.php?actions=<?= htmlspecialchars($action); ?>&id=<?= intval($picture['id']); ?>">
				<?php
					$likeImg = ($action == 'dislike') ? CAMAGRU_ROOT.'/img/like_active.png' : CAMAGRU_ROOT.'/img/like_inactive.png';
					$active = ($action == 'dislike') ? 'active' : '';
				?>
				<img class="<?= $active; ?> like-mini" src=<?= htmlspecialchars($likeImg); ?> alt="like"/>
				</a>
				<span class='nbLikes'><?= intval($user->getNbLikes($db, $picture['id']));?></span>
				<img class='comment-mini' src="<?= CAMAGRU_ROOT ?>/img/comments.png" alt="comment" />
				<span class='nbComments'><?= intval($user->getNbComments($db, $picture['id'])); ?></span>
			</div>

			<hr>
			<div id="comments">
				<?php $comments = $user->getComments($db, intval($picture['id']));
				if (!empty($comments))
				{
					foreach ($comments as $comment) { ?>
						<span class='writer'><strong><?= htmlspecialchars($user->getUsername($db, intval($comment['user_id']))); ?></strong></span>
						<?php if ($user->verifyMyComment($db, intval($comment['id']))) { ?>
						<img class='deleteCom-mini' src="<?= CAMAGRU_ROOT ?>/img/delete.png" alt="delete" />
						<?php } ?>
						<div class='content'>
							<p id=<?= intval($comment['id']); ?> class='words'><?= htmlspecialchars($comment['comment']); ?></p>
						</div>
						<p class='date'><?= htmlspecialchars(date('M jS Y, H:i', strtotime($comment['created_at']))); ?></p>
					<?php }
				} else { ?>
					<p id='empty'>There is no comment yet</p>
				<?php } ?>
			</div>
			<form action="scripts/actions.php" method="POST" id="send-comment">
				<input type="text" name="content" placeholder="Your comment..." />
				<button type="submit" name="send" value=<?= intval($picture['id']); ?>>Send</button>
			</form>
		</div>
	</div>
</div>
<?php } ?>