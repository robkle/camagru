<?php

enum PswdRenewStatus
{
	case Success;
	case Unauthorised;
	case InvalidPassword;
	case ConflictPassword;
	case SystemFailure;
}

class PswdRenewOutputData 
{
	public $user_id;

	public function __construct($user_id)
	{
		$this->user_id = $user_id;
	}	
}
