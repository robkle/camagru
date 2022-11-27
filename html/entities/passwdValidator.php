<?php

class PasswdValidator
{
	public static function passwd_format($pswd)
	{
		$pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/";
		return (preg_match($pattern, $pswd));
	}

	public static function passwd_encrypt($pswd)
	{
		return (password_hash($pswd, PASSWORD_DEFAULT, ['cost'=>12]));

	}

	public static function passwd_verify($pswd, $hash)
	{
		return (password_verify($pswd, $hash));
	}
}
