<?php
class User
{
	private $user_id;

	public function __construct($user_id)
	{
		$this->user_id = $user_id;
	}

	public function like($db, $username, $photo_id)
	{
		$db->query('INSERT INTO likes SET like_user = ? WHERE photo_id = ?', [$username, $photo_id]);
	}

	public function getNbLikes($db, $photo_id)
	{
		$nbLikes = $db->query('SELECT * FROM likes WHERE photo_id = ?', [$photo_id])->rowCount();
		return $nbLikes;
	}

	public function comment($db, $username, $comment, $photo_id)
	{
		$db->query('INSERT INTO comments SET writer = ?, comment = ? WHERE photo_id = ?', [$username, $comment, $photo_id]);
	}

	public function getNbComments($db, $photo_id)
	{
		$nbComments = $db->query('SELECT * FROM comments WHERE photo_id = ?', [$photo_id])->rowCount();
		return $nbComments;
	}

	public function insertPhoto($db, $photo)
	{
		$db->query('INSERT INTO photos SET content = ?, user_id = ?', [$photo, $this->user_id]);
	}

	public function getPhotos($db, $photo = false)
	{
		if ($photo == false)
		{
			$photos = $db->query('SELECT * FROM photos WHERE user_id = ?', [$this->user_id])->fetchAll();
			return $photos;
		}
		else
		{
			$photo = $db->query('SELECT * FROM photos WHERE user_id = ?, content = ?', [$this->user_id, $photo])->fetch();
			return $photo;
		}
	}

	public function getPhotoOfAllUsers($db)
	{
		$photo = $db->query('SELECT * FROM photos')->fetch();
		return $photo;
	}

	public function delete($db, $table, $id)
	{
		$req = $db->query("DELETE FROM $table WHERE id = ? AND user_id = ?", [$id, $this->user_id]);
		if ($req)
			return true;
		return false;
	}
	// public function upload($db,)
	// {

	// }
}
?>