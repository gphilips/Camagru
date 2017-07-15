<?php

class Validate
{
	private $post;
	private $errors = [];

	public function __construct($post)
	{
		foreach ($post as $key => $value) {
			if (is_numeric($value))
				$post[$key] = intval($value);
			else
				$post[$key] = htmlspecialchars($value);
		}
		$this->post = $post;
	}

	private function getInput($input)
	{
		if (!isset($this->post[$input]))
			return NULL;

		return $this->post[$input];
	}

	public function isAlphanum($input, $flashError)
	{
		if (!preg_match('/^([a-zA-Z0-9_]){8,}$/', $this->getInput($input)))
			$this->errors[$input] = $flashError;
	}

	public function isUnique($input, $db, $table, $flashError = false)
	{
		$exist = $db->query("SELECT id FROM $table WHERE $input = ?", [$this->getInput($input)])->fetch();

		if ($exist && $flashError == false)
			return (!$exist) ? false : $exist['id'];
		if ($exist)
			$this->errors[$input] = $flashError;
	}

	public function isEmail($input, $flashError)
	{
		if (!filter_var($this->getInput($input), FILTER_VALIDATE_EMAIL))
			$this->errors[$input] = $flashError;
	}

	public function isPassword($input, $flashError)
	{
		if (!preg_match('/^(.){8,}$/', $this->getInput($input)))
			$this->errors[$input] = $flashError;
	}

	public function isConfirmed($input, $flashError)
	{
		$value = $this->getInput($input);

		if (empty($value) || $value != $this->getInput($input.'_confirm'))
			$this->errors[$input] = $flashError;
	}



	public function isValid()
	{
		return empty($this->errors);
	}

	public function getErrors()
	{
		return $this->errors;
	}
}
?>