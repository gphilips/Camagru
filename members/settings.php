<?php
require '../auth/access_restricted.php';
require '../templates/header.php';

$user = new User($_SESSION['auth']['id']);
$onOff = $user->getReceiveMail($db, $_SESSION['auth']['id']);
print_r($onOff);
$checked = ($onOff == 1) ? "checked" : "";
?>

<div id="settings">

	<form action='scripts/actions.php' id="receiveMail" method="GET">
		<label>
			<input type="checkbox" name="receiveMail" onchange="this.form.submit()"  value=<?= ($onOff == 1) ? 0 : 1;?> <?= $checked ?> />
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