<?php
class User
{
	private $user_id;

	public function __construct($user_id)
	{
		$this->user_id = $user_id;
	}

	public function getUsername($db, $user_id)
	{
		$username = $db->query('SELECT username FROM users WHERE id = ?', [$user_id])->fetchColumn();
		return $username;
	}

	public function setLike($db, $photo_id)
	{
		$db->query('INSERT INTO likes SET user_id = ?, photo_id = ?', [$this->user_id, $photo_id]);
	}

	public function deleteLike($db, $photo_id)
	{
		$req = $db->query("DELETE FROM likes WHERE user_id = ? AND photo_id = ?", [$this->user_id, $photo_id]);
	}

	public function getMyLike($db, $photo_id)
	{
		$myLike = $db->query('SELECT * FROM likes WHERE user_id = ? AND photo_id = ?', [$this->user_id, $photo_id])->rowCount();
		return $myLike;
	}

	public function getNbLikes($db, $photo_id)
	{
		$nbLikes = $db->query('SELECT * FROM likes WHERE photo_id = ?', [$photo_id])->rowCount();
		return $nbLikes;
	}

	public function setComment($db, $username, $comment, $photo_id)
	{
		$db->query('INSERT INTO comments SET writer = ?, comment = ?, created_at = NOW() WHERE photo_id = ?', [$username, $comment, $photo_id]);
	}

	public function getNbComments($db, $photo_id)
	{
		$nbComments = $db->query('SELECT * FROM comments WHERE photo_id = ?', [$photo_id])->rowCount();
		return $nbComments;
	}

	public function setPhoto($db, $photo)
	{
		$db->query('INSERT INTO photos SET content = ?, user_id = ?, created_at = NOW()', [$photo, $this->user_id]);
	}

	public function verifyMyPhoto($db, $photo_id)
	{
		$photo = $db->query('SELECT * FROM photos WHERE user_id = ? AND id = ?', [$this->user_id, $photo_id])->rowCount();
		return $photo;
	}

	public function getMyPhotos($db)
	{
		$photos = $db->query('SELECT * FROM photos WHERE user_id = ?', [$this->user_id])->fetchAll();
		return $photos;
	}

	public function getPhotoOfAllUsers($db, $start, $nbPhotos)
	{
		$photo = $db->query("SELECT * FROM photos ORDER BY created_at DESC LIMIT $start, $nbPhotos")->fetchAll();
		return $photo;
	}

	public function nbPhotoOfAllUsers($db)
	{
		$nbPhoto = $db->query("SELECT id FROM photos")->rowCount();
		return $nbPhoto;
	}

	public function delete($db, $table, $id)
	{
		$req = $db->query("DELETE FROM $table WHERE id = ? AND user_id = ?", [$id, $this->user_id]);
		return ($req) ? true : false;
	}
}
?>