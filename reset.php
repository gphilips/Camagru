<?php 
require 'auth/reset_password.php';
require 'templates/header.php';
?>

<div id="no-scroll">
	<div class="bg-img"></div>

	<div class="title">
		<h1><img src='img/logo-camagru.png' alt="logo-camagru"> CAMAGRU</h1>
		<h2>Reset the password</h2>
	</div>
	<div class="login-bloc">
		<form action="index.php" method="POST">
			<input type="password" name="pwd" placeholder="New password">
			<input type="password" name="confirm-pwd" placeholder="Confirm the new password">
			<input type="submit" value="Reset my password">
		</form>
	</div>
</div>

<?php require 'templates/footer.php'; ?>