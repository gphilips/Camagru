<?php 
	require 'templates/autoload.php';
	require_once 'config/setup.php';

	$db = App::getDatabase($pdo);
	
	if (!empty($_POST)){
		
		$validate = new Validate($_POST);
		
		$validate->isAlphanum('username',"Your username is not valid (8 alphanumeric characters minimum)");
		if ($validate->isValid())
			$validate->isUnique('username', $db, 'users', "Your username is already taken");

		$validate->isEmail('email', "Your email is not valid (alphanumeric characters)");
		if ($validate->isValid())
			$validate->isUnique('email', $db, 'users', "Your email is already taken for an other user");

		$validate->isPassword('pwd', "Your password is not valid (8 characters minimum)");
		$validate->isConfirmed('pwd', "You didn't set your password two times");

		$verify = 0;

		if ($validate->isValid())
		{
			$session = Session::getInstance();
			$auth = new Auth($session);
			$auth->register($db, $_POST['username'], $_POST['pwd'], $_POST['email']);
			
			$session->setFlash('success', "Your account has been successfully registered ! We have sent you an email confirmation.");

			App::redirect('index.php');
		}
		else
			$errors = $validate->getErrors();
	}
?>