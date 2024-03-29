<?php
require 'auth/autoconnect.php';
require 'templates/header.php';
?>

<div id="no-scroll">
	<div class="bg-img"></div>

	<div class="title">
		<h1><img src='img/logo-camagru.png' alt="logo-camagru"> CAMAGRU</h1>
		<h2>Connecting the world through photos</h2>
	</div>

	<div class="login-bloc">
		<form action="<?= CAMAGRU_ROOT ?>/members/account.php" method="POST">
			<input type="text" name="username" placeholder="Username or Email">
			<input type="password" name="pwd" placeholder="Password">
			<input type="submit" value="Log In">
			<div id="remember">
				<input type="checkbox" name="remember" checked>Remember me
			</div>
		</form>
		<div class="detail">
			<p><a href="forget.php">Forgotten password?</a></p>
			<p><a href="register.php"><span>Register</span></a></p>
		</div>
	</div>
</div>
<?php require 'templates/footer.php'; ?>
