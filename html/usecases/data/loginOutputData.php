<?php

enum LoginStatus
{
	case Success;
	case InvalidLogin;
	case InvalidPassword;
	case AccountUnconfirmed;
	case SystemFailure;
}

class LoginOutputData
{
	public $userid;
	public $username;

	function create($userid, $username)
	{
		$this->userid = $userid;
		$this->username = $username;
	}
}
