<?php 
require 'auth/access_restricted.php';
require 'members/actions.php';
require 'templates/header.php';
?>

<div id="snap">
	<video id="webcam"></video>
	<canvas id="canvas"></canvas>
	<button id="take"><img src="img/cam_icon.png"/></button>
</div>

<div id="sidebar">
	<h2><?php echo $_SESSION['auth']['username']; ?>'s photos</h2>
	<div id="mini-pictures">
	<?php 
	if (!empty($photos)) {
		foreach ($photos as $photo) {
	?>
		<img class="miniature" id=<?php echo $photo['id']; ?> src=<?php echo $photo['content']; ?> />
	<?php }
	} ?>
	</div>
</div>

<script type="text/javascript" src="js/webcam.js"></script>
<?php require 'templates/footer.php'; ?>