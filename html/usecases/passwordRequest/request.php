<?php

require_once __DIR__.'/../../entities/tokenHandler.php';
require_once __DIR__.'/../../entities/outputStatus.php';

class PswdRequestInputData
{
	public $email;
	public $data_access;
	public $message_handler;
	public $output_view;
	public $presenter;

	function __construct($email, $data_access, $message_handler, $output_view, $presenter)
	{
		$this->email = $email;
		$this->data_access = $data_access;
		$this->message_handler = $message_handler;
		$this->output_view = $output_view;
		$this->presenter = $presenter;
	}
}

interface PswdRequestInterface
{
		public static function run(PswdRequestInputData $requestdata);
		public static function check(PswdRequestInputData $requestdata);
}

class PswdRequestOutputData
{
	public $email;
	public $token;

	function __construct($email, $token)
	{
		$this->email = $email;
		$this->token = $token;
	}
}

interface PswdRequestOutput
{
	public function pswdRequestOutput(PswdRequestStatus $status, PswdRequestViewModel $pswdRequest_view);
}

class PswdRequestInteractor implements PswdRequestInterface
{
	public static function run($requestdata)
	{
		$status = PswdRequestInteractor::check($requestdata);		
		$requestdata->presenter->pswdRequestOutput($status, $requestdata->output_view);
	}

	public static function check($requestdata)
	{
		$db_user = $requestdata->data_access->fetchUser(null, null, $requestdata->email);
		if ($db_user === []) {
			return Status::SystemFailure;
		}
		if ($db_user['email'] === null) {
			return Status::InvalidEmail;
		}
		if ($requestdata->data_access->deleteRequestToken($db_user['email']) !== true)
		{
			return Status::SystemFailure;
		}
		$token = Tokens::createToken();
		if ($requestdata->data_access->postRequestToken($db_user['email'], $token, date("U") + 1800) !== true)
		{
			return Status::SystemFailure;
		}
		$info = new PswdRequestOutputData($requestdata->email, bin2hex($token));
		if ($requestdata->message_handler->pswdRequestEmail($info) !== true) {
			return Status::SystemFailure;
		}
		return Status::Success;
	}
}
