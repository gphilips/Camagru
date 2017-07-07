<?php 
require 'templates/autoload.php';
require 'templates/header.php';
?>

<div id="no-scroll">
	<div class="bg-img"></div>

	<div class="title">
		<h1><img src='img/logo-camagru.png' alt="logo-camagru"> CAMAGRU</h1>
		<h2>Reset the password</h2>
	</div>
	<div class="login-bloc">
		<form action="auth/reset_password.php?id=<?= $_GET[id] ?>&token=<?= $_GET[token] ?>" method="POST">
			<input type="password" name="pwd" placeholder="New password">
			<input type="password" name="pwd-confirm" placeholder="Confirm the new password">
			<input type="submit" value="Reset my password">
		</form>
	</div>
</div>

<?php require 'templates/footer.php'; ?>