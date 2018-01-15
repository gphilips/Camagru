<?php
require '../auth/access_restricted.php';
require '../templates/header.php';

$user = new User($_SESSION['auth']['id']);
$onOff = $user->getReceiveMail($db, $_SESSION['auth']['id']);
?>

<div id="settings">

	<form action='scripts/actions.php' id="receivedMail" method="GET">
		<label>
			<input type="submit" name="receiveMail" value=<?= ($onOff == 1) ? "Off" : "On" ?> <?= ($onOff == 1) ? "class='btnActivate'" : "" ?> />
		Be notified by email when i receive a comment
		</label>
	</form>

	<form action='scripts/actions.php' method="POST">
		<input type="text" name="username" placeholder=<?= htmlspecialchars($user->getUsername($db, intval($_SESSION['auth']['id'])))?> />
		<input type="submit" value="Change my username">
	</form>

	<form action=scripts/actions.php method="POST">
		<input type="submit" name="resetPwd" value="Reset my password">
	</form>
</div>

<?php require '../templates/footer.php'; ?>