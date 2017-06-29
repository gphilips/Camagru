<?php
	spl_autoload_register('autoloader');

	function autoloader($class)
	{
		require $_SERVER['DOCUMENT_ROOT']."/camagru/class/$class.php";
	}
?>