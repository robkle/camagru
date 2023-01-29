<?php

enum PswdRequestStatus
{
	case Success;
	case InvalidEmail;
	case SystemFailure;
}

class PswdRequestOutputData
{
	public $email;
	public $token;

	function __construct($email, $token)
	{
		$this->email = $email;
		$this->token = $token;
	}
}
