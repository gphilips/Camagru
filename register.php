<?php 
require 'functions/auth.php'; 
require 'templates/header.php';
?>

<div id="no-scroll">
	<div class="bg-img"></div>
	
	<?php if (!empty($errors)){ ?>
	<div class="alert-danger">
		<p>You didn't fill the form correctly:</p>
		<ul>
			<?php foreach($errors as $error){ ?>
				<li><?= $error; ?></li>
			<?php } ?>
		</ul>
	</div>
	<?php } ?>

	<div class="title">
		<h1><img src='img/logo-camagru.png' alt="logo-camagru"> CAMAGRU</h1>
		<h2>Register</h2>
	</div>

	<div class="register-bloc">
		<form action="#" method="POST">
			<input type="text" name="username" placeholder="Username">
			<input type="email" name="email" placeholder="Email">
			<input type="password" name="pwd" placeholder="Password">
			<input type="password" name="confirm-pwd" placeholder="Confirm password">
			
			<input type="submit" value="Register">
		</form>
	</div>
</div>

<?php require 'templates/footer.php'; ?>