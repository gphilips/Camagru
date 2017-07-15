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

	public function setComment($db, $comment, $photo_id)
	{
		$db->query('INSERT INTO comments SET comment = ?, user_id = ?, created_at = NOW(), photo_id = ?', [$comment, $this->user_id, $photo_id]);
	}

	public function verifyMyComment($db, $comment_id)
	{
		$comment = $db->query('SELECT * FROM comments WHERE user_id = ? AND id = ?', [$this->user_id, $comment_id])->rowCount();
		return (!$comment) ? 0 : 1;
	}

	public function getComments($db, $photo_id)
	{
		$comments = $db->query('SELECT * FROM comments WHERE photo_id = ?', [$photo_id])->fetchAll();
		return $comments;
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
		return (!$photo) ? 0 : 1;
	}

	public function getMyPhotos($db)
	{
		$photos = $db->query('SELECT * FROM photos WHERE user_id = ?', [$this->user_id])->fetchAll();
		return $photos;
	}

	public function getPhoto($db, $photo_id)
	{
		$photo = $db->query('SELECT * FROM photos WHERE id = ?', [$photo_id])->fetch();
		return $photo;
	}

	public function getPhotoOfUser($db, $start, $nbPhotos, $user_id)
	{
		$photo = $db->query("SELECT * FROM photos WHERE user_id = ? ORDER BY created_at DESC LIMIT $start, $nbPhotos", [$user_id])->fetchAll();
		return $photo;
	}

	public function nbPhotoOfUser($db, $user_id)
	{
		$nbPhoto = $db->query("SELECT id FROM photos WHERE user_id = ?", [$user_id])->rowCount();
		return $nbPhoto;
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