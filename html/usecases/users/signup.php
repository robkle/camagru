<?php

require_once __DIR__.'/../../entities/outputStatus.php';
require_once __DIR__.'/../../entities/usernameValidator.php';
require_once __DIR__.'/../../entities/passwdValidator.php';
require_once __DIR__.'/../../entities/tokenHandler.php';

class UserInputData
{
	public $login;
	public $email;
	public $pswd;
	public $pswd2;
	public $data_access;
	public $message_handler;
	public $output_view;
	public $presenter;

	function __construct($input, $data_access,  $message_handler, $output_view, $presenter)
	{
		if (array_key_exists('login', $input))
		{
			$this->login = $input['login'];
		}
		if (array_key_exists('email', $input))
		{
			$this->email = $input['email'];
		}
		if (array_key_exists('pswd', $input))
		{
			$this->pswd = $input['pswd'];
		}
		if (array_key_exists('pswd2', $input))
		{
			$this->pswd2 = $input['pswd2'];
		}
		$this->data_access = $data_access;
		$this->message_handler = $message_handler;
		$this->output_view = $output_view;
		$this->presenter = $presenter;
	}
}

interface UserInteractor
{ 
	public static function run(UserInputData $userdata);
	public static function check(UserInputData $userdata);
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

interface UserOutput
{
	public function signupOutput(SignupStatus $status, SignupViewModel $signup_view);
}

class SignupInteractor implements UserInteractor
{
	public static function run($userdata)
	{
		$status = self::check($userdata);
		$userdata->presenter->signupOutput($status, $userdata->output_view);
	}

	public static function check($userdata)
	{
		if (UsernameValidator::username_format($userdata->login) !== 1) {
			return Status::InvalidLogin;
		}
		if (filter_var($userdata->email, FILTER_VALIDATE_EMAIL) === false) {
			return Status::InvalidEmail;
		}
		if ($userdata->pswd !== $userdata->pswd2) {
			return Status::ConflictPassword;
		}
		if (PasswdValidator::passwd_format($userdata->pswd) !== 1) {
			return Status::InvalidPassword;
		}
		$db_user = $userdata->data_access->fetchUser(null, $userdata->login, $userdata->email);
		if ($db_user === [NULL]) {
			return Status::SystemFailure;
		}
		if ($db_user['login'] === $userdata->login) {
			return Status::ExistingLogin;
		}
		if ($db_user['email'] === $userdata->email) {
			return Status::ExistingEmail;
		}
		$ckey = Tokens::createCkey($userdata->login);
		if ($userdata->data_access->postUser($userdata->login, $userdata->email, PasswdValidator::passwd_encrypt($userdata->pswd), $ckey) !== TRUE) {
			return Status::SystemFailure;
		}
		$info = new SignupOutputData($userdata->login, $userdata->email, $ckey);
		if ($userdata->message_handler->signupEmail($info) !== TRUE) {
			return Status::SystemFailure;
		}
		return Status::Success;
	}
}
