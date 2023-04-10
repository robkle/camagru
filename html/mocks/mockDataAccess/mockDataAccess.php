<?php

require_once __DIR__.'/../../usecases/interfaces/dataAccessInterface.php';

class MockDataAccess implements DataAccess
{
	public function fetchUser($userId, $user, $email): array
	{	
		$db_user = [];
		if (($handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv", "r")) !== FALSE)
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
				if (strpos($data, $userId) or strpos($data, $user) or strpos($data, $email))
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
		}
		fclose($handle);
		return $db_user;
	}

	public function postUser($login, $email, $enc_pswd, $ckey): bool
	{
		$SUCCESS = false;
		if (($handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv", "a")) !== FALSE)
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
		if (($handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv", "r")) !== FALSE)
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
		if (($handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv", "r")) !== FALSE)
		{
			if (($handle_tmp = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/.tmpusers.csv", "a")) !== FALSE)
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
		rename("/home/robkle/Projects/camagru/html/mocks/mockDatabase/.tmpusers.csv", "/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv");
		return $SUCCESS;
	}

	public function postImage($userId, $image): bool
	{
		$SUCCESS = FALSE;
		if (($handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/images.csv", "a")) !== FALSE)
		{
			$img_id = rand(1000, 9999);
			$img = [$img_id, $userId, $image];
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
		$handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/pswdRequest.csv", "a");
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
		if (($handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/pswdRequest.csv", "r")) !== FALSE)
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
		if (($handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/pswdRequest.csv", "r")) !== FALSE)
		{
			if (($handle_tmp = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/.tmpPswdRequest.csv", "a")) !== FALSE)
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
		rename("/home/robkle/Projects/camagru/html/mocks/mockDatabase/.tmpPswdRequest.csv", "/home/robkle/Projects/camagru/html/mocks/mockDatabase/pswdRequest.csv");
		return $SUCCESS;
	}

	public function changePassword($user_id, $enc_pswd): bool 
	{
		$SUCCESS = FALSE;
		if (($handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv", "r")) !== FALSE)
		{
			if (($handle_tmp = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/.tmpusers.csv", "a")) !== FALSE)
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
		rename("/home/robkle/Projects/camagru/html/mocks/mockDatabase/.tmpusers.csv", "/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv");
		return $SUCCESS;
	}

	public function changeUsername($user_id, $new_username): bool 
	{
		$SUCCESS = FALSE;
		if (($handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv", "r")) !== FALSE)
		{
			if (($handle_tmp = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/.tmpusers.csv", "a")) !== FALSE)
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
		rename("/home/robkle/Projects/camagru/html/mocks/mockDatabase/.tmpusers.csv", "/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv");
		return $SUCCESS;
	}

	public function changeEmail($user_id, $new_email): bool 
	{
		$SUCCESS = FALSE;
		if (($handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv", "r")) !== FALSE)
		{
			if (($handle_tmp = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/.tmpusers.csv", "a")) !== FALSE)
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
		rename("/home/robkle/Projects/camagru/html/mocks/mockDatabase/.tmpusers.csv", "/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv");
		return $SUCCESS;
	}

	public function changeNotifications($user_id, $notifications): bool 
	{
		$SUCCESS = FALSE;
		if (($handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv", "r")) !== FALSE)
		{
			if (($handle_tmp = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/.tmpusers.csv", "a")) !== FALSE)
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
		rename("/home/robkle/Projects/camagru/html/mocks/mockDatabase/.tmpusers.csv", "/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv");
		return $SUCCESS;
	}
}
