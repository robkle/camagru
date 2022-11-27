<?php

class Ckey
{
	public static function create (string $user)
	{
		return  md5(time() . $user); 
	}
}
