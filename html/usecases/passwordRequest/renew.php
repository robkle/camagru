<?php

require_once __DIR__.'/../../entities/passwdValidator.php';
require_once __DIR__.'/../../entities/outputStatus.php';

class PswdRenewInputData
{
	public $user_id;
	public $pswd;
	public $pswd2;
	public $data_access;
	public $output_view;
	public $presenter;
	

	function __construct($user_id, $input, $data_access, $output_view, $presenter)
	{
		if ($input && array_key_exists('pswd', $input))
		{
			$this->pswd = $input['pswd'];
		}
		if ($input && array_key_exists('pswd2', $input))
		{
			$this->pswd2 = $input['pswd2'];
		}
		$this->user_id = $user_id;
		$this->data_access = $data_access;
		$this->output_view = $output_view;
		$this->presenter = $presenter;
	}
}

interface PswdRenewInterface
{
	public static function run(PswdRenewInputData $input);
	public static function check(PswdRenewInputData $input);
}

class PswdRenewOutputData 
{
	public $user_id;

	public function __construct($user_id)
	{
		$this->user_id = $user_id;
	}	
}

interface PswdRenewOutput
{
	public function pswdRenewOutput(PswdRenewStatus $status, PswdRenewOutputData $session_user, PswdRenewViewModel $output_view);
}

class PswdRenewInteractor implements PswdRenewInterface
{
	public static function run($renewdata)
	{
		$status = PswdRenewInteractor::check($renewdata);
		$sessionUser = new PswdRenewOutputData($renewdata->user_id);
		$renewdata->presenter->pswdRenewOutput($status, $sessionUser, $renewdata->output_view);
	}

	public static function check($renewdata)
	{
		if ($renewdata->pswd !== $renewdata->pswd2) {
			return Status::ConflictPassword;
		}
		if (PasswdValidator::passwd_format($renewdata->pswd) !== 1) {
			return Status::InvalidPassword;
		}
		if ($renewdata->data_access->changePassword($renewdata->user_id, PasswdValidator::passwd_encrypt($renewdata->pswd)) !== TRUE) {
			return Status::SystemFailure;
		}
		return Status::Success;
	}
}
