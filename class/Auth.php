<?php

class Auth
{
	private $session;

	public function __construct($session)
	{
		$this->session = $session;
	}

	public function register($db, $username, $password, $email)
	{
		$password = hash('whirlpool', $password);	
		$token = bin2hex(random_bytes(50));
		
		$req = $db->query('INSERT INTO users SET username = ?, password = ?, email = ?, confirm_token = ?', [$username, $password, $email, $token]);

		$user_id = $db->lastInsertId();

		$subject = "Confirmation of your account";
		$link = "http://localhost:8888/camagru/scripts/confirm.php?id=$user_id&token=.$token";
		$message = "Please click this link to activate your account\r\n$link\r\n";
		$header = "MIME-Version: 1.0\r\n";
		$header .= "X-Mailer: PHP/" . PHP_VERSION;
		$header .= "Content-type: text/html; charset=iso-8859-1'\r\n";
		$header .= "To: $username <$email>\r\n";
		$header .= "From: Greg Philips <greg.philips08@gmail.com>\r\n";
		$header .= "Reply-To: Greg Philips <greg.philips08@gmail.com>\r\n";

		mail($email, $subject, $message, $header);
	}

	public function confirm($db, $user_id, $user_token, $session)
	{
		$req = $db->query('SELECT * FROM users WHERE id = ?',[$user_id]);
		$user = $req->fetch();

		if ($user && $user['confirm_token'] == $user_token)
		{
			$req = $db->query("UPDATE users SET confirm_token = NULL, confirm_at = NOW() WHERE id = ?", [$user_id]);

			$session->write_session('auth', $user);
			
			return true;
		}
		return false;
	}

	public function restrict()
	{
		if(!$this->session->read_session('auth'))
		{
			$this->session->setFlash('danger', "Make sure you confirm your account and you logged in correctly");
			App::redirect('index.php');
		}
	}

	public function isConnected()
	{
		if (!$this->session->read_session('auth'))
			return false;
		return $this->session->read_session('auth');
	}

	public function connect($user)
	{
		$this->session->write_session('auth', $user);
	}

	public function cookieAutoConnect($db)
	{
		if (isset($_COOKIE['remember']) && !$this->isConnected())
		{
			$remember_token = $_COOKIE['remember'];
			$parts = explode('==', $remember_token);
			$user_id = $parts[0];
			$req = $db->query("SELECT * FROM users WHERE id = ?", [$user_id]);
			$user = $req->fetch();
			
			if ($user)
			{
				$result = $user_id.'=='.$user['remember_token'];
				
				if ($result == $remember_token)
				{
					$this->connect($user);
					setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7);
					//App::redirect('account.php');
				}
				else
					setcookie('remember', NULL, -1);
			}
			else
				setcookie('remember', NULL, -1);
		}
	}

	public function rememberToken($db, $user_id)
	{
		$remember_token = bin2hex(random_bytes(50));
		$req = $db->query("UPDATE users SET remember_token = ? WHERE id = ?",[$remember_token, $user_id]);
		setcookie('remember', $user_id.'=='.$remember_token, time() + 60 * 60 * 24 * 7);
	}

	public function login($db, $username, $password, $remember)
	{
		$req = $db->query("SELECT * FROM users WHERE (username = :username OR email = :username) AND confirm_at IS NOT NULL", ['username' => $username]);
		$user = $req->fetch();
		if (hash('whirlpool', $password) == $user['password'])
		{
			$this->connect($user);

			if ($remember)
				$this->rememberToken($db, $user['id']);

			return $user;
		}
		else
			return false;
	}

	public function resetPassword($db, $email)
	{
		$req = $db->query("SELECT * FROM users WHERE email = ? AND confirm_at IS NOT NULL", [$email]);
		$user = $req->fetch();

		if ($user) {
			$reset_token = bin2hex(random_bytes(50));
			$req = $db->query("UPDATE users SET reset_token = ?, reset_at = NOW() WHERE id = ?", [$reset_token, $user['id']]);

			$subject = "Reset your password";
			$link = "http://localhost:8888/camagru/scripts/reset_password.php?id=".$user['id']."&token=".$reset_token;
			$message = "Please click this link to reset your password</br><a href=\"$link\">http://camagru.com/reset</a>";

			$headers  = "MIME-Version: 1.0" . "\r\n";
	 		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
	 		$headers .= "From: Greg <gphilips@student.42.fr>" . "\r\n";
	 		$headers .=  "Reply-To: gphilips@student.42.fr" . "\r\n";

			mail($_POST['email'], $subject, $message, $headers);

			return $user;
		} 
		else
			return false;
	}

	public function TokenGetUser($db, $user_id, $user_token)
	{
		$req = $db->query("SELECT * FROM users WHERE id = ? AND reset_token IS NOT NULL AND reset_token = ? AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)", [$user_id, $user_token]);
		$user = $req->fetch();
	}
}

?>