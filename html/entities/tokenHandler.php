<?php

class Tokens 
{
	public static function createCkey(string $user)
	{
		return  md5(time() . $user); 
	}

	public static function createToken()
	{
		return random_bytes(32);
	}

	public static function tokenCompare($a, $b)
	{
    	$diff = strlen($a) ^ strlen($b);
    	for($i = 0; $i < strlen($a) && $i < strlen($b); $i++)
    	{
        	$diff |= ord($a[$i]) ^ ord($b[$i]);
    	}
    	return $diff === 0;
	}
}
