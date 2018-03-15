<?php

class Auth
{
	private $session;

	public function __construct($session)
	{
		$this->session = $session;
	}

	private function getUserById($db, $user_id)
	{
		$req = $db->query('SELECT * FROM users WHERE id = ?',[$user_id]);
		$user = $req->fetch();
		return $user;
	}

	public function register($db, $username, $password, $email)
	{
		$password = hash('whirlpool', $password);	
		$token = sha1(uniqid($username, true));
		
		$req = $db->query('INSERT INTO users SET username = ?, password = ?, email = ?, confirm_token = ?', [$username, $password, $email, $token]);

		$user_id = $db->lastInsertId();

		$subject = "Confirmation of your account";
		$link = "http://localhost:$_SERVER[SERVER_PORT]/camagru/auth/confirm.php?id=$user_id&token=".$token;
		$message = "
		<table width='100%' border='0' cellspacing='0' cellpadding='0'>
			<tr>
				<td style='text-align: center;'>
					<h1>Confirm your account</h1>
					<p>Thank you for registering on <strong>Camagru</strong> !</p>
					<p>Please click this link to confirm your account:<p>
					<a href=\"$link\">https://www.camagru.com/confirm</a>
				</td>
			</tr>
		<table>
		";

		$headers  = "MIME-Version: 1.0" . "\r\n";
 		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
 		$headers .= "From: Camagru <no-reply@camagru.com>" . "\r\n";
 		$headers .=  "Reply-To: gphilips@student.42.fr" . "\r\n";

		mail($email, $subject, $message, $headers);
	}

	public function confirm($db, $user_id, $user_token, $session)
	{
		$user = $this->getUserById($db, $user_id);

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
			App::redirect('/camagru/index.php');
		}
	}

	public function isConnected($db)
	{
		$isAuth = $this->session->read_session('auth');
		$user = $this->getUserById($db, $isAuth['id']);
		return ($user ? true : false);
	}

	public function connect($user)
	{
		$this->session->write_session('auth', $user);
	}

	public function cookieAutoConnect($db)
	{
		if (isset($_COOKIE['remember']) && $_COOKIE['remember'] && !$this->isConnected())
		{
			$remember_token = $_COOKIE['remember'];
			$parts = explode('==', $remember_token);
			$user_id = $parts[0];
			$user = $this->getUserById($db, $user_id);

			if ($user)
			{
				$result = $user_id.'=='.$user['remember_token'];
				
				if ($result == $remember_token)
				{
					$this->connect($user);
					setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7, '/members');
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
		$remember_token = bin2hex(rand(0, 50));
		$db->query("UPDATE users SET remember_token = ? WHERE id = ?",[$remember_token, $user_id]);
		setcookie('remember', $user_id.'=='.$remember_token, time() + 60 * 60 * 24 * 7, '/camagru/members/account.php');
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

	public function loginVisitor($db)
	{
		$req = $db->query("SELECT * FROM users WHERE username = :username", ['username' => 'visitor']);
		$user = $req->fetch();
		$this->session->write_session('no-auth', $user);
	}

	public function resetPassword($db, $email)
	{
		$req = $db->query("SELECT * FROM users WHERE email = ? AND confirm_at IS NOT NULL", [$email]);
		$user = $req->fetch();

		if ($user)
		{
			$reset_token = sha1(uniqid($user['id'], true));
			$db->query("UPDATE users SET reset_token = ?, reset_at = NOW() WHERE id = ?", [$reset_token, $user['id']]);

			$subject = "Reset your password";
			$link = "http://localhost:$_SERVER[SERVER_PORT]/camagru/reset.php?id=$user[id]&token=".$reset_token;
			$message = "
			<table width='100%' border='0' cellspacing='0' cellpadding='0'>
				<tr>
					<td style='text-align: center;'>
						<h1>Reset your password</h1>
						<p>Please click this link to reset your password:<p>
						<a href=\"$link\">https://www.camagru.com/reset</a>
					</td>
				</tr>
			<table>
			";

			$headers  = "MIME-Version: 1.0" . "\r\n";
	 		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
	 		$headers .= "From: Camagru <no-reply@camagru.com>" . "\r\n";
	 		$headers .=  "Reply-To: gphilips@student.42.fr" . "\r\n";

			mail($email, $subject, $message, $headers);
			return $user;
		} 
		else
			return false;
	}

	public function TokenGetUser($db, $user_id, $user_token)
	{
		$req = $db->query("SELECT * FROM users WHERE id = ? AND reset_token IS NOT NULL AND reset_token = ? AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)", [$user_id, $user_token]);
		$user = $req->fetch();
		return $user;
	}

	public function changeField($db, $user_id, $field, $newState)
	{
		$user = $this->getUserById($db, $user_id);

		if ($user)
		{
			$db->query("UPDATE users SET $field = ? WHERE id = ?",[$newState, $user_id]);
			return true;
		}
		return false;
	}
}

?>