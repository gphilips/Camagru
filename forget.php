<?php 
require 'auth/forget_password.php';
require 'templates/header.php';
?>

<div id="no-scroll">
	<div class="bg-img"></div>

	<div class="title">
		<h1><img src='img/logo-camagru.png' alt="logo-camagru"> CAMAGRU</h1>
		<h2>Forgotten my password</h2>
	</div>

	<div class="login-bloc">
		<form action="#" method="POST">
			<input type="email" name="email" placeholder="email">
			<input type="submit" value="Reset my password">
		</form>
	</div>
</div>

<?php require 'templates/footer.php'; ?>