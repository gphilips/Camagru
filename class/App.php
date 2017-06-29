<?php 

class App {

	static $db = NULL;

	static function getDatabase($pdo)
	{
		if (!self::$db)
			self::$db = new Database($pdo);
		
		return self::$db;
	}

	static function redirect($page)
	{
		header("Location: $page");
		exit();
	}
}
?>