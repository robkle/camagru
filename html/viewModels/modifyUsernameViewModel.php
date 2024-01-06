<?php

class ModifyUsernameViewModel
{
	public $err_msg;
	public $user_id;
	public $username;

	function create ($err_msg, $session_user = null)
	{
		$this->err_msg = $err_msg;
		if (isset($session_user))
		{
			$this->user_id = $session_user->userid;
			$this->username = $session_user->username;
		}
	}
}
