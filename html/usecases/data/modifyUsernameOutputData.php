<?php

enum ModifyUsernameStatus
{
	case Success;
	case Unauthorised;
	case InvalidUsername;
	case SystemFailure;
}

class ModifyUsernameOutputData
{
	public $userid;
	public $username;

	function create($userid, $username)
	{
		$this->userid = $userid;
		$this->username = $username;
	}
}
