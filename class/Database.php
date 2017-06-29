<?php

class Database
{
	private $pdo = NULL;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

	public function query($query, $params = false)
	{
			if ($params)
			{
				$req = $this->pdo->prepare($query);
				$req->execute($params);
			}
			else
				$req = $this->pdo->query($query);
			return $req;
	}


	public function lastInsertId()
	{
		return $this->pdo->lastInsertId();
	}
}

?>