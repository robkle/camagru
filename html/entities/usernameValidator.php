<?php

class UsernameValidator
{
	public static function username_format($username)
	{
		if (strlen($username) >= 5) {
			return (1);
		}
		return (0);
	}

}
