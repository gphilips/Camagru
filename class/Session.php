<?php
class Session
{
	static $instance;

	static function getInstance()
	{
		if (!self::$instance)
			self::$instance = new Session();
		return self::$instance;
	}

	public function __construct()
	{
		session_start();
	}

	public function setFlash($key, $flashMsg)
	{
		$_SESSION['flash'][$key] = $flashMsg;
	}

	public function hasFlashes()
	{
		return isset($_SESSION['flash']);
	}

	public function getFlashes()
	{
		$flash = $_SESSION['flash'];
		unset($_SESSION['flash']);
		return $flash;
	}

	public function write_session($key, $value)
	{
		$_SESSION[$key] = $value;
	}

	public function read_session($key)
	{
		return isset($_SESSION[$key]) ? $_SESSION[$key] : NULL;
	}

	public function delete_session($key)
	{
		unset($_SESSION[$key]);
	}
}
?>