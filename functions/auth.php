<?php 

	if (!empty($_POST)){
		require_once 'config/setup.php';

		$username = $_POST['username'];
		$email = $_POST['email'];
		$password = $_POST['pwd'];
		
		$errors = array();
		$verify = 0;

		if (empty($username) || !preg_match('/^([a-zA-Z0-9_]){8,}$/', $username)){
			$errors['username'] = "Your username is not valid (8 alphanumeric characters minimum)";
		} else {
			$req = $pdo->prepare('SELECT id FROM users WHERE username = ?');
			$req->execute([$username]);
			$user = $req->fetch();

			if ($user) {
				$errors['username'] = "Your username is already taken";
			}
		}

		if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
			$errors['email'] = "Your email is not valid (alphanumeric characters)";
		} else {
			$req = $pdo->prepare('SELECT id FROM users WHERE email = ?');
			$req->execute([$email]);
			$user = $req->fetch();

			if ($user) {
				$errors['email'] = "Your email is already taken";
			}
		}

		if (empty($password)){
			$errors['pwd'] = "You didn't set a password";
		}
		else if (!preg_match('/^(.){8,}$/', $password) || $password != $_POST['confirm-pwd']){
			$errors['pwd'] = "'Your password is not valid (8 characters minimum)";
		}

		if (empty($errors)){

			$req = $pdo->prepare("INSERT INTO users SET username = ?, password = ?, email = ?, confirm_token = ?");

			$password = hash('whirlpool', $password);	
			$token = bin2hex(random_bytes(50));
			
			$req->execute([$username, $password, $email, $token]);

			$user_id = $pdo->lastInsertId();
			
			$subject = "Confirmation of your account";
			$message = "
			<html>
				<head>
					<title>Confirmation of your account</title>
				</head>
				<body>
					<p>In order to validate your account, we please you to click on this link</p>
					<p>
					<p>
						<a href=http://localhost:8888/camagru/confirm.php?id=".$user_id."&token=".$token.">
						http://localhost:8888/camagru/confirm.php?id=".$user_id."&token=".$token.";
					</p>
				</body>
		    </html>
		    ";
							
			$headers  = 'MIME-Version: 1.0' . "\n";
     		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
     		$headers .= "From: Greg <gphilips@student.42.fr>" . "\n";
			

			mail($email, $subject, $message, $headers);
			
			$_SESSION['flash']['success'] = "Your account has been successfully registered ! We have sent you a confirmation email.";
			header('Location: index.php');
			exit();
		}
	}