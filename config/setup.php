<?php

require_once 'database.php';

try
{
	$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = '
		CREATE DATABASE IF NOT EXISTS `db_camagru` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
			USE `db_camagru`;
		';

	$pdo->exec($sql);
}
catch(Exception $e)
{
	die('Erreur : '.$e->getMessage());
}

$userTable = $pdo->query("SHOW TABLES LIKE 'users'")->fetchColumn();	
if ($userTable != 'users')
{
	$sql = '
		CREATE TABLE IF NOT EXISTS `users` (
		  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
		  `username` varchar(255) NOT NULL,
		  `email` varchar(255) NOT NULL,
		  `password` varchar(255) NOT NULL,
		  `confirm_token` varchar(255) DEFAULT NULL,
		  `confirm_at` datetime DEFAULT NULL,
		  `reset_token` varchar(255) DEFAULT NULL,
		  `reset_at` datetime DEFAULT NULL,
		  `remember_token` varchar(255) DEFAULT NULL,
		  `receiveMail` tinyint(1) NOT NULL DEFAULT 1
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		';

	$pdo->exec($sql);

	$sql = "INSERT INTO `users` (`id`, `username`, `email`, `password`, `confirm_token`, `confirm_at`, `reset_token`, `reset_at`, `remember_token`, `receiveMail`) VALUES
(2, 'gphilips', 'greg.philips08@gmail.com', '1480baeda2ae0cb462acf86488798d105a940859d0818c91ca73454ed89fd8ab64f02d1a4cb7480419f2349a5502d0ec15a029b22bb61608c4b7d0e83a86026a', NULL, '2017-06-29 02:54:48', '961495dc4adb04fb01ddd62b786f08cdc74fdcfbfb6668331c', '2017-07-01 01:54:50', '45f2a17dc3f4ecf0c7f96743ae340667f1b8616ad4ecf9892d', 1);";

	$pdo->exec($sql);

	$sql = "INSERT INTO `users` (`id`, `username`, `email`, `password`, `confirm_token`, `confirm_at`, `reset_token`, `reset_at`, `remember_token`, `receiveMail`) VALUES ('1', 'visitor', '', '', NULL, NULL, NULL, NULL, NULL, '1');";

	$pdo->exec($sql);

	$sql = '
		CREATE TABLE IF NOT EXISTS `photos` (
		  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
		  `content` longtext NOT NULL,
		  `created_at` datetime NOT NULL,
		  `user_id` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		';

	$pdo->exec($sql);

	$sql = '
		CREATE TABLE IF NOT EXISTS `likes` (
		  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
		  `user_id` int(11) NOT NULL,
		  `photo_id` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		';

	$pdo->exec($sql);

	$sql = '
		CREATE TABLE IF NOT EXISTS `comments` (
		  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
		  `comment` text NOT NULL,
		  `user_id` int(11) NOT NULL,
		  `created_at` datetime NOT NULL,
		  `photo_id` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		';

	$pdo->exec($sql);

	$sql = '
	CREATE INDEX `idx_photos_user` ON `photos` (`user_id`);
	CREATE INDEX `idx_likes_user` ON `likes` (`user_id`);
	CREATE INDEX `idx_comments_photo` ON `comments` (`photo_id`);
	';

	$pdo->exec($sql);

	$sql = '
	ALTER TABLE `comments`
  	ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`photo_id`) REFERENCES `photos` (`id`) ON DELETE CASCADE;

	ALTER TABLE `likes`
  	ADD CONSTRAINT `likes_photos` FOREIGN KEY (`photo_id`) REFERENCES `photos` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

	ALTER TABLE `photos`
  	ADD CONSTRAINT `photos_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
  	';

  	$pdo->exec($sql);
}
?>