<?php

enum SignupStatus
{
	case Success;
	case InvalidLogin;
	case ExistingLogin;
	case InvalidEmail;
	case ExistingEmail;
	case InvalidPassword;
	case ConflictPassword;
	case SystemFailure;
}

class SignupOutputData
{
	public $login;
	public $email;
	public $ckey;

	function __construct($login, $email, $ckey)
	{
		$this->login = $login;
		$this->email = $email;
		$this->ckey = $ckey;
	}
}

