<?php

require_once __DIR__.'/../../entities/tokenHandler.php';
require_once __DIR__.'/../../entities/outputStatus.php';

class PswdResetInputData
{
	public $token;
	public $data_access;
	public $output_view;
	public $presenter;
	

	function __construct($input, $data_access, $output_view, $presenter)
	{
		if ($input && array_key_exists('token', $input))
		{
			$this->token = $input['token'];
		}
		$this->data_access = $data_access;
		$this->output_view = $output_view;
		$this->presenter = $presenter;
	}
}

interface PswdResetInterface
{
	public static function run(PswdResetInputData $resetdata);
	public static function check(PswdResetInputData $resetdata, PswdResetOutputData &$sessionUser);
}

class PswdResetOutputData
{
	public $userid;

	function create($userid)
	{
		$this->userid = $userid;
	}
}

interface PswdResetOutput
{
	public function pswdResetOutput(PswdResetStatus $status, PswdResetOutputData $session_user, PswdResetViewModel $output_view);
}

class PswdResetInteractor implements PswdResetInterface
{
	public static function run($resetdata)
	{
		$sessionUser = new PswdResetOutputData();
		$status = PswdResetInteractor::check($resetdata, $sessionUser);
		$resetdata->presenter->pswdResetOutput($status, $sessionUser, $resetdata->output_view);
	}

	public static function check($resetdata, &$sessionUser)
	{
		//Check if url query string exists
		if ($resetdata->token == null) {
			return Status::QueryInvalid;
		}
		//fetch token array containing token, timeout and email
		$db_token = $resetdata->data_access->fetchRequestToken($resetdata->token);
		if ($db_token === []) {
			return Status::SystemFailure; 
		}
		//Check timeout
		if ($db_token['timeout'] < date("U")) {
			return Status::TimeOut;
		}
		//fetch user by email
		$dbUser = $resetdata->data_access->fetchUser(null, null, $db_token['email']);
		if ($dbUser === []) {
			return Status::SystemFailure;
		}
		//Check if user exists
		if ($dbUser['email'] == NULL) {
			return Status::InvalidEmail;
		} 
		$sessionUser->create($dbUser['id']);
		return Status::Success;
	}
}
