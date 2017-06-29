<?php 
require 'scripts/access_restricted.php';
require 'templates/header.php';
?>

<h1>Bonjour <?php echo($_SESSION['auth']['username']); ?></h1>

<?php require 'templates/footer.php'; ?>