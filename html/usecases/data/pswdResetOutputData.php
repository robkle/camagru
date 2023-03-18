<?php

enum PswdResetStatus
{
	case Success;
	case QueryInvalid;
	case TimeOut;
	case InvalidEmail;
	case SystemFailure;
}

class PswdResetOutputData
{
	public $userid;

	function create($userid)
	{
		$this->userid = $userid;
	}
}
