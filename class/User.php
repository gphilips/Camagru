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

	public function alertComment($db, $photo_id, $content)
	{
		$username = $db->query('SELECT username FROM users WHERE id = ?', [$this->user_id])->fetchColumn();
		$owner_id = $db->query('SELECT user_id FROM photos WHERE id = ?', [$photo_id])->fetchColumn();
		if ($owner_id > 0)
			$owner_email = $db->query('SELECT email FROM users WHERE id = ?', [$owner_id])->fetchColumn();
		if ($username && $owner_email)
		{
			$subject = "New comment on your photo";
			$link = "http://localhost:$_SERVER[SERVER_PORT]/camagru/members/gallery.php?id=$photo_id";
			$message = "
			<table width='100%' border='0' cellspacing='0' cellpadding='0'>
				<tr>
					<td style='text-align: center;'>
						<h1>You have received a new comment!</h1>
						<p><strong>$username</strong> said to you: \"$content\"</p>
						<p>Please, click this link to reply to this message:<p>
						<a href=\"$link\">https://www.camagru.com/members/photo</a>
					</td>
				</tr>
			<table>
			";

			$headers  = "MIME-Version: 1.0" . "\r\n";
	 		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
	 		$headers .= "From: Camagru <no-reply@camagru.com>" . "\r\n";
	 		$headers .=  "Reply-To: gphilips@student.42.fr" . "\r\n";

			mail($owner_email, $subject, $message, $headers);

			return $user;
		} 
		else
			return false;
	}
}
?>