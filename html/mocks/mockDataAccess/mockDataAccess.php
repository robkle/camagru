<?php

require_once __DIR__.'/../../entities/dataAccessInterface.php';

class MockDataAccess implements DataAccess
{
	public $path;

	public function __construct($path = null)
	{
		if ($path == null) {
			$this->path = "/home/robkle/Projects/camagru/html";
		} else {
			$this->path = $path;
		}
	}

	public function fetchUser($userId, $user, $email): array
	{	
		$db_user = [];
		if (($handle = fopen($this->path."/mocks/mockDatabase/users.csv", "r")) !== FALSE)
		{
			$db_user = ["id" => null,
						"login" => null,
						"email" => null,
						"pswd" => null,
						"confirm" => null,
						"ckey" => null,
						"notifications" => null];
			while (($line = fgetcsv($handle, null, ",")) !== FALSE)
			{
				$data=json_encode($line);
				if (($userId && strpos($data, $userId)) || ($user && strpos($data, $user)) || ($email && strpos($data, $email)))
				{
					$db_user = ["id" => $line[0],
								"login" => $line[1],
								"email" => $line[2],
								"pswd" => $line[3],
								"confirm" => $line[4],
								"ckey" => $line[5],
								"notifications" => $line[6]];
					break;	
				}
			}	
		fclose($handle);
		}
		return $db_user;
	}

	public function postUser($login, $email, $enc_pswd, $ckey): bool
	{
		$SUCCESS = false;
		if (($handle = fopen($this->path."/mocks/mockDatabase/users.csv", "a")) !== FALSE)
		{
			$user_id = rand(1000, 9999);
			$user = [$user_id, $login, $email, $enc_pswd, "No", $ckey, "On"];
			if (fputcsv($handle, $user) !== FALSE)
			{
				$SUCCESS = true;
			}
		}
		fclose($handle);
		return $SUCCESS;
	}

	public function fetchCkey($ckey): array
	{
		$db_ckey = [];
		if (($handle = fopen($this->path."/mocks/mockDatabase/users.csv", "r")) !== FALSE)
		{
			$db_ckey = ["confirm" => null, "ckey" => null];
			while (($line = fgetcsv($handle, null, ",")) !== FALSE)
			{
				$data=json_encode($line);
				if (strpos($data, $ckey) !== FALSE)
				{
					$db_ckey = ["confirm" => $line[4], "ckey" => $line[5]];
					break;	
				}
			}	
		}
		fclose($handle);
		return $db_ckey;
	}

	public function confirmUser($ckey): bool 
	{
		$SUCCESS = FALSE;
		if (($handle = fopen($this->path."/mocks/mockDatabase/users.csv", "r")) !== FALSE)
		{
			if (($handle_tmp = fopen($this->path."/mocks/mockDatabase/.tmpusers.csv", "a")) !== FALSE)
			{
				while (($line = fgetcsv($handle, null, ",")) !== FALSE)
				{
					if ($line[5] == $ckey) {
						$line[4] = "Yes";
					}
					fputcsv($handle_tmp, $line);
				}
				$SUCCESS = TRUE;
			}
			fclose($handle_tmp);
		}
		fclose($handle);
		rename($this->path."/mocks/mockDatabase/.tmpusers.csv", $this->path."/mocks/mockDatabase/users.csv");
		return $SUCCESS;
	}

	public function postImage($userId, $image): bool
	{
		$SUCCESS = FALSE;
		if (($handle = fopen($this->path."/mocks/mockDatabase/images.csv", "a")) !== FALSE)
		{
			$img_id = rand(1000, 9999);
			$img = [$img_id, $userId, $image, "0"]; //0 is the number likes
			if (fputcsv($handle, $img) !== FALSE)
			{
				$SUCCESS = true;
			}
		}
		fclose($handle);
		return $SUCCESS;

	}

	public function postRequestToken($email, $token, $timeout): bool
	{
		$SUCCESS = FALSE;
		$handle = fopen($this->path."/mocks/mockDatabase/pswdRequest.csv", "a");
		if ($handle !== FALSE){
			$_id = rand(1000, 9999);
			$line = [$_id, $email, $token, $timeout];
			if (fputcsv($handle, $line) !== FALSE) {
				$SUCCESS = TRUE;
			}
		}
		fclose($handle);
		return $SUCCESS;
	}

	public function fetchRequestToken($token): array
	{
		$db_token = [];
		if (($handle = fopen($this->path."/mocks/mockDatabase/pswdRequest.csv", "r")) !== FALSE)
		{
			$db_token = ["id" => null, "email" => null, "token" => null, "timeout" => null];
			while (($line = fgetcsv($handle, null, ",")) !== FALSE)
			{
				$data=json_encode($line);
				//In MySQL implementation Token::tokenCompare should be used
				if (strpos($data, $token) !== FALSE)
				{
					//token is stored as hex in csv, but will be bin in mysql
					$db_token = ["id" => $line[0], "email" => $line[1], "token" => $line[2], "timeout" => $line[3]];
					break;	
				}
			}	
		}
		fclose($handle);
		return $db_token;
	}

	public function deleteRequestToken($email): bool
	{
		$SUCCESS = FALSE;
		if (($handle = fopen($this->path."/mocks/mockDatabase/pswdRequest.csv", "r")) !== FALSE)
		{
			if (($handle_tmp = fopen($this->path."/mocks/mockDatabase/.tmpPswdRequest.csv", "a")) !== FALSE)
			{
				while (($line = fgetcsv($handle, null, ",")) !== FALSE)
				{
					if ($line[1] != $email) {
						fputcsv($handle_tmp, $line);
					}
				}
				$SUCCESS = TRUE;
			}
			fclose($handle_tmp);
		}
		fclose($handle);
		rename($this->path."/mocks/mockDatabase/.tmpPswdRequest.csv", $this->path."/mocks/mockDatabase/pswdRequest.csv");
		return $SUCCESS;
	}

	public function changePassword($user_id, $enc_pswd): bool 
	{
		$SUCCESS = FALSE;
		if (($handle = fopen($this->path."/mocks/mockDatabase/users.csv", "r")) !== FALSE)
		{
			if (($handle_tmp = fopen($this->path."/mocks/mockDatabase/.tmpusers.csv", "a")) !== FALSE)
			{
				while (($line = fgetcsv($handle, null, ",")) !== FALSE)
				{
					if ($line[0] == $user_id) {
						$line[3] = $enc_pswd;
					}
					fputcsv($handle_tmp, $line);
				}
				$SUCCESS = TRUE;
			}
			fclose($handle_tmp);
		}
		fclose($handle);
		rename($this->path."/mocks/mockDatabase/.tmpusers.csv", $this->path."/mocks/mockDatabase/users.csv");
		return $SUCCESS;
	}

	public function changeUsername($user_id, $new_username): bool 
	{
		$SUCCESS = FALSE;
		if (($handle = fopen($this->path."/mocks/mockDatabase/users.csv", "r")) !== FALSE)
		{
			if (($handle_tmp = fopen($this->path."/mocks/mockDatabase/.tmpusers.csv", "a")) !== FALSE)
			{
				while (($line = fgetcsv($handle, null, ",")) !== FALSE)
				{
					if ($line[0] == $user_id) {
						$line[1] = $new_username;
					}
					fputcsv($handle_tmp, $line);
				}
				$SUCCESS = TRUE;
			}
			fclose($handle_tmp);
		}
		fclose($handle);
		rename($this->path."/mocks/mockDatabase/.tmpusers.csv", $this->path."/mocks/mockDatabase/users.csv");
		return $SUCCESS;
	}

	public function changeEmail($user_id, $new_email): bool 
	{
		$SUCCESS = FALSE;
		if (($handle = fopen($this->path."/mocks/mockDatabase/users.csv", "r")) !== FALSE)
		{
			if (($handle_tmp = fopen($this->path."/mocks/mockDatabase/.tmpusers.csv", "a")) !== FALSE)
			{
				while (($line = fgetcsv($handle, null, ",")) !== FALSE)
				{
					if ($line[0] == $user_id) {
						$line[2] = $new_email;
					}
					fputcsv($handle_tmp, $line);
				}
				$SUCCESS = TRUE;
			}
			fclose($handle_tmp);
		}
		fclose($handle);
		rename($this->path."/mocks/mockDatabase/.tmpusers.csv", $this->path."/mocks/mockDatabase/users.csv");
		return $SUCCESS;
	}

	public function changeNotifications($user_id, $notifications): bool 
	{
		$SUCCESS = FALSE;
		if (($handle = fopen($this->path."/mocks/mockDatabase/users.csv", "r")) !== FALSE)
		{
			if (($handle_tmp = fopen($this->path."/mocks/mockDatabase/.tmpusers.csv", "a")) !== FALSE)
			{
				while (($line = fgetcsv($handle, null, ",")) !== FALSE)
				{
					if ($line[0] == $user_id) {
						$line[6] = $notifications;
					}
					fputcsv($handle_tmp, $line);
				}
				$SUCCESS = TRUE;
			}
			fclose($handle_tmp);
		}
		fclose($handle);
		rename($this->path."/mocks/mockDatabase/.tmpusers.csv", $this->path."/mocks/mockDatabase/users.csv");
		return $SUCCESS;
	}

	public function fetchImageInfo($image_id): array
	{	
		$db_image = [];
		if (($handle = fopen($this->path."/mocks/mockDatabase/images.csv", "r")) !== FALSE)
		{
			$db_image = ["id" => null,
						"user_id" => null,
						"image" => null];
			while (($line = fgetcsv($handle, null, ",")) !== FALSE)
			{
				$data=json_encode($line);
				if (strpos($data, $image_id))
				{
					$db_image = ["id" => $line[0],
								"user_id" => $line[1],
								"image" => $line[2]];
					break;
				}
			}	
		}
		fclose($handle);
		return $db_image;
	}

	public function postComment($image_id, $user_id, $comment): bool
	{
		$SUCCESS = false;
		if (($handle = fopen($this->path."/mocks/mockDatabase/comments.csv", "a")) !== FALSE)
		{
			$comment_id = rand(1000, 9999);
			$comment = [$comment_id, $image_id, $user_id, $comment];
			if (fputcsv($handle, $comment) !== FALSE)
			{
				$SUCCESS = true;
			}
		}
		fclose($handle);
		return $SUCCESS;
	}

	public function fetchLike($image_id, $user_id): array
	{
		$like = [];
		if (($handle = fopen($this->path."/mocks/mockDatabase/likes.csv", "r")) !== FALSE)
		{
			$like = ["like_id" => null, "image_id" => null, "user_id" => null];
			while (($line = fgetcsv($handle, null, ",")) !== FALSE)
			{
				$data = json_encode($line);
				if (strpos($data, $image_id) and strpos($data, $user_id))
				{
					$like = ["like_id" => $data[0], "image_id" => $data[1], "user_id" => $data[2]];
					break;				}
			}
		}
		fclose($handle);
		return $like;
	}

	public function addLike($image_id, $user_id): bool
	{
		$SUCCESS = false;
		if (($handle = fopen($this->path."/mocks/mockDatabase/likes.csv", "a")) !== FALSE)
		{
			$like_id = rand(1000, 9999);
			$like = [$like_id, $image_id, $user_id];
			if (fputcsv($handle, $like) !== FALSE)
			{
				$SUCCESS = $this->countLike($image_id, 1);
			}
			fclose($handle);
			return $SUCCESS;
		}
	}

	public function removeLike($image_id, $user_id): bool
	{
		$SUCCESS = FALSE;
		if (($handle = fopen($this->path."/mocks/mockDatabase/likes.csv", "r")) !== FALSE)
		{
			if (($handle_tmp = fopen($this->path."/mocks/mockDatabase/.tmplikes.csv", "a")) !== FALSE)
			{
				while (($line = fgetcsv($handle, null, ",")) !== FALSE)
				{
					if ($line[1] == $image_id && $line[2] == $user_id) {
						continue;
					}
					fputcsv($handle_tmp, $line);
				}
				$SUCCESS = $this->countLike($image_id, -1);
			}
			fclose($handle_tmp);
		}
		fclose($handle);
		rename($this->path."/mocks/mockDatabase/.tmplikes.csv", $this->path."/mocks/mockDatabase/likes.csv");
		return $SUCCESS;
	}

	protected function countLike($image_id, $count): bool
	{
		$SUCCESS = FALSE;
		if (($img_handle = fopen($this->path."/mocks/mockDatabase/images.csv", "r")) == FALSE)
		{
			return $SUCCESS;
		}
		if (($handle_tmp = fopen($this->path."/mocks/mockDatabase/.tmpimages.csv", "a")) == FALSE)
		{
			return $SUCCESS;
		}
		while (($line = fgetcsv($img_handle, null, ",")) !== FALSE)
		{
			if ($line[0] == $image_id) 
			{
				$likes = intval($line[3]);
				$line[3] = strval($likes + $count);
				break;
			}
		}
		if (fputcsv($handle_tmp, $line) == FALSE)
		{
			return $SUCCESS;
		}
		$SUCCESS = TRUE;
		fclose($handle_tmp);
		fclose($img_handle);
		rename($this->path."/mocks/mockDatabase/.tmpimages.csv", $this->path."/mocks/mockDatabase/images.csv");
		return $SUCCESS;
	}

	public function fetchImages($creator = NULL)
	{
		$images = array();
		if (($handle = fopen($this->path."/mocks/mockDatabase/images.csv", "r")) !== FALSE)
		{
			while (($line = fgetcsv($handle, null, ",")) !== FALSE)
			{
				if ($line[1] === $creator or $creator === NULL) {	
					$image = [
						"id" => $line[0],
						"creator_id" => $line[1],
						"path" => $line[2],
						"likes" => $line[3]];
								
					$creatorData = $this->fetchUser($line[1], null, null);
					$image["creator"] = $creatorData["login"];
					array_push($images, $image);
				}
			}
		} else {
			return NULL;
		}
		fclose($handle);
		return $images;	
	}

	public function fetchImage($image_id): array
	{
		$image = [];
		if (($handle = fopen($this->path."/mocks/mockDatabase/images.csv", "r")) !== FALSE)
		{
			$image = [
					"id" => null, 
					"creator_id" => null, 
					"creator" => null,
					"path" => null,
					"likes" => null];
	
			while (($line = fgetcsv($handle, null, ",")) !== FALSE)
			{
				$data = json_encode($line);
				if (strpos($data, $image_id)) {	
					$image = [
						"id" => $line[0],
						"creator_id" => $line[1],
						"path" => $line[2],
						"likes" => $line[3]];
								
					$creatorData = $this->fetchUser($line[1], null, null);
					$image["creator"] = $creatorData["login"];
					break;
				}
			}
		}
		fclose($handle);
		return $image;	
	}

	public function fetchComments($image_id) 
	{
		$comments = array();
		if (($handle = fopen($this->path."/mocks/mockDatabase/comments.csv", "r")) !== FALSE)
		{
			while (($line = fgetcsv($handle, null, ",")) !== FALSE)
			{
				if ($line[1] === $image_id) {
					$comment = [
						"comment_id" => $line[0],
						"image_id" => $line[1],
						"user_id" => $line[2],
						"comment" => $line[3],
					];
					$commenterData = $this->fetchUser($line[2], null, null);
					$comment["commenter"] = $commenterData["login"];
					array_push($comments, $comment);
				}
			}
		} else {
			return NULL;
		}
		fclose($handle);
		return $comments;
	}

	public function removeImageLikes($image_id): bool
	{
		$SUCCESS = FALSE;
		if (($handle = fopen($this->path."/mocks/mockDatabase/likes.csv", "r")) !== FALSE)
		{
			if (($handle_tmp = fopen($this->path."/mocks/mockDatabase/.tmplikes.csv", "a")) !== FALSE)
			{
				while (($line = fgetcsv($handle, null, ",")) !== FALSE)
				{
					if ($line[1] == $image_id) {
						continue;
					}
					fputcsv($handle_tmp, $line);
				}
				$SUCCESS = TRUE;
			}
			fclose($handle_tmp);
		}
		fclose($handle);
		rename($this->path."/mocks/mockDatabase/.tmplikes.csv", $this->path."/mocks/mockDatabase/likes.csv");
		return $SUCCESS;
	}

	public function removeComments($image_id): bool
	{
		$SUCCESS = FALSE;
		if (($handle = fopen($this->path."/mocks/mockDatabase/comments.csv", "r")) !== FALSE)
		{
			if (($handle_tmp = fopen($this->path."/mocks/mockDatabase/.tmpcomments.csv", "a")) !== FALSE)
			{
				while (($line = fgetcsv($handle, null, ",")) !== FALSE)
				{
					if ($line[1] == $image_id) {
						continue;
					}
					fputcsv($handle_tmp, $line);
				}
				$SUCCESS = TRUE;
			}
			fclose($handle_tmp);
		}
		fclose($handle);
		rename($this->path."/mocks/mockDatabase/.tmpcomments.csv", $this->path."/mocks/mockDatabase/comments.csv");
		return $SUCCESS;
	}

	public function removeImage($image_id, $user_id): bool
	{
		$SUCCESS = FALSE;
		if (($handle = fopen($this->path."/mocks/mockDatabase/images.csv", "r")) !== FALSE)
		{
			if (($handle_tmp = fopen($this->path."/mocks/mockDatabase/.tmpimages.csv", "a")) !== FALSE)
			{
				while (($line = fgetcsv($handle, null, ",")) !== FALSE)
				{
					if ($line[0] == $image_id and $line[1] == $user_id) {
						continue;
					}
					fputcsv($handle_tmp, $line);
				}
				$SUCCESS = TRUE;
			}
			fclose($handle_tmp);
		}
		fclose($handle);
		rename($this->path."/mocks/mockDatabase/.tmpimages.csv", $this->path."/mocks/mockDatabase/images.csv");
		$this->removeComments($image_id);
		$this->removeImageLikes($image_id);
		return $SUCCESS;
	}
}
