<?php

class MockSignupInput
{
	
	static function userSuccess(): array 
	{
		$userSuccess = array(
			"login" => "user",
			"email" => "user@domain.com",
			"pswd" => "#Qwerty12345!",
			"pswd2" => "#Qwerty12345!",
			);
		return $userSuccess;
	}
}
