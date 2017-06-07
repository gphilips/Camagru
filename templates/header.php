<?php 
if (session_status() == PHP_SESSION_NONE){
	session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Capture and share your photos accross the world">
	<meta name="author" content="gphilips">

	<title>Camagru - Connecting the world through photos</title>

	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
</head>
<body>

	<nav>
		<ul>
			<div id="logo">
				<a href='#'><img src='img/logo-camagru.png' alt="logo-camagru"></a>
			</div>
			<div id="links">
				<li>
					<a href="register.php">Register</a>
				</li>
				<li>
					<a href="index.php">Log In</a>
				</li>

			</div>
		</ul>
	</nav>

	<?php if (isset($_SESSION['flash'])) { ?>
		<?php foreach ($_SESSION as $type => $message) { ?>
			<div class="alert-".<?= $type; ?>.">
				<?= $message; ?>
			</div>
	
		<?php } ?>
		<?php unset($_SESSION['flash']); ?>
	<?php } ?>

