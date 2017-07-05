<?php define('CAMAGRU_ROOT', '/camagru') ?>
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
	<link href="<?= CAMAGRU_ROOT ?>/css/style.css" rel="stylesheet">
</head>
<body>

	<nav>
		<ul>
			<div id="logo">
				<a href="<?= CAMAGRU_ROOT ?>/index.php"><img src="<?= CAMAGRU_ROOT ?>/img/logo-camagru.png" alt="logo-camagru"></a>
			</div>
			<div id="links">
				<?php if ($session->read_session('auth')) { ?>
					<li>
						<a href="<?= CAMAGRU_ROOT ?>/members/gallery.php">Gallery</a>
					</li>
					<li>
						<a href="<?= CAMAGRU_ROOT ?>/members/myaccount.php">My account</a>
					</li>
					<li>
						<a href="<?= CAMAGRU_ROOT ?>/templates/logout.php">Log out</a>
					</li>
				<?php } else { ?>
					<li>
						<a href="<?= CAMAGRU_ROOT ?>/register.php">Register</a>
					</li>
					<li>
						<a href="<?= CAMAGRU_ROOT ?>/index.php">Log in</a>
					</li>
				<?php } ?>
			</div>
		</ul>
	</nav>

	<?php if ($session->hasFlashes()) { ?>
		<?php foreach ($session->getFlashes() as $type => $message) { ?>
			<div class="alert-<?= $type; ?>">
				<p><?= $message; ?></p>
			</div>
	
		<?php } ?>
	<?php } ?>

