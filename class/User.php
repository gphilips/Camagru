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
		$nbLikes = $db->query('SELECT COUNT(like_users) FROM photos WHERE photo_id = ?', [$photo_id]);
		return $nbLikes;
	}

	public function comment($db, $username, $comment, $photo_id)
	{
		$db->query('INSERT INTO likes SET writer_id = ?, comment = ? WHERE photo_id = ?', [$username, $comment, $photo_id]);
	}

	public function getNbComments($db, $photo_id)
	{
		$nbComments = $db->query('SELECT COUNT(comment) FROM photos WHERE photo_id = ?', [$photo_id]);
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
			$photos = $db->query('SELECT * FROM photos WHERE user_id = ? LIMIT 12', [$this->user_id])->fetchAll();
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

	// public function upload($db,)
	// {

	// }
}
?>