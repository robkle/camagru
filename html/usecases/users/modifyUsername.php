<?php

require_once __DIR__.'/../../entities/outputStatus.php';
require_once __DIR__.'/../../entities/usernameValidator.php';

class ModifyUsernameInputData
{
	public $user_id;
	public $username;
	public $data_access;
	public $output_view;
	public $presenter;
	

	function __construct($user_id, $input, $data_access, $output_view, $presenter)
	{
		if ($input && array_key_exists('username', $input))
		{
			$this->username = $input['username'];
		}
		if ($input && array_key_exists('pswd', $input))
		{
			$this->pswd = $input['pswd'];
		}
		$this->user_id = $user_id;
		$this->data_access = $data_access;
		$this->output_view = $output_view;
		$this->presenter = $presenter;
	}
}

interface ModifyUsernameInterface
{
	public static function run(ModifyUsernameInputData $modifydata);
	public static function check($modifydata, &$sessionUser);
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

interface modifyUsernameOutput
{
	public function modifyUsernameOutput(ModifyUsernameStatus $status, ModifyUsernameOutputData $session_user, ModifyUsernameViewModel $output_view);
}

class ModifyUsernameInteractor implements ModifyUsernameInterface
{
	public static function run($modifydata)
	{
		$sessionUser = new ModifyUsernameOutputData();
		$status = ModifyUsernameInteractor::check($modifydata, $sessionUser);
		$modifydata->presenter->modifyUsernameOutput($status, $sessionUser, $modifydata->output_view);
	}

	public static function check($modifydata, &$sessionUser)
	{
		if (strlen($modifydata->user_id) == 0) {
			return Status::Unauthorised;
		}
		if (UsernameValidator::username_format($modifydata->username) !== 1) {
			return Status::InvalidUsername;
		}
		$dbUser = $modifydata->data_access->fetchUser($modifydata->user_id, null, null);
		if ($dbUser === []) {
			return Status::SystemFailure;
		}
		if ($dbUser['id'] === null) {
			return Status::Unauthorised;
		}
		if (PasswdValidator::passwd_verify($modifydata->pswd, $dbUser['pswd']) !== true) {
			return Status::InvalidPassword;
		}
		if ($modifydata->data_access->changeUsername($modifydata->user_id, $modifydata->username)!== TRUE) {
			return Status::SystemFailure;
		}
		$sessionUser->create($modifydata->user_id, $modifydata->username);		
		return Status::Success;
	}
}
