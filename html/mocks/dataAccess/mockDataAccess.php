<?php

require_once __DIR__.'/../../usecases/interfaces/dataAccessInterface.php';

class MockDataAccess implements DataAccess
{
	public function fetchUser($userId, $user, $email): array
	{	
		$db_user = [];
		if (($handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv", "r")) !== FALSE)
		{
			$db_user = ["id" => null, "login" => null, "email" => null, "pswd" => null, "confirm" => null, "ckey" => null];
			while (($line = fgetcsv($handle, null, ",")) !== FALSE)
			{
				$data=json_encode($line);
				if (strpos($data, $userId) !== FALSE or strpos($data, $user) !== FALSE or strpos($data, $email) !== FALSE)
				{
					$db_user = ["id" => $line[0], "login" => $line[1], "email" => $line[2], "pswd" => $line[3], "confirm" => $line[4], "ckey" => $line[5]];
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
			$user = [$user_id, $login, $email, $enc_pswd, "No", $ckey];
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
}
