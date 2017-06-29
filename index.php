<?php
require 'scripts/autoconnect.php';
require 'templates/header.php';
?>

<div id="no-scroll">
	<div class="bg-img"></div>

	<div class="title">
		<h1><img src='img/logo-camagru.png' alt="logo-camagru"> CAMAGRU</h1>
		<h2>Connecting the world through photos</h2>
	</div>

	<div class="login-bloc">
		<form action="account.php" method="POST">
			<input type="text" name="username" placeholder="Username or Email">
			<input type="password" name="pwd" placeholder="Password">
			<input type="submit" value="Log In">
		</form>
		<div class="detail">
			<div id="remember">
				<input type="checkbox" name="remember" value="1"> Remember me
			</div>
			<p><a href="forget.php">Forgotten password?</a></p>
			<p><a href="register.php"><span>Register</span></a></p>
		</div>
	</div>
</div>
<?php require 'templates/footer.php'; ?>
