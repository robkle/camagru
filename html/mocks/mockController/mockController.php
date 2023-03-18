<?php

require_once __DIR__.'/../../usecases/data/userInputData.php';
require_once __DIR__.'/../../usecases/data/confirmInputData.php';
require_once __DIR__.'/../../usecases/data/loginInputData.php';
require_once __DIR__.'/../../usecases/data/uploadInputData.php';
require_once __DIR__.'/../../usecases/data/pswdRequestInputData.php';
require_once __DIR__.'/../../usecases/data/pswdResetInputData.php';
require_once __DIR__.'/../../usecases/data/pswdRenewInputData.php';
require_once __DIR__.'/../../usecases/signup/signup.php';
require_once __DIR__.'/../../usecases/signup/confirm.php';
require_once __DIR__.'/../../usecases/login/login.php';
require_once __DIR__.'/../../usecases/upload/upload.php';
require_once __DIR__.'/../../usecases/passwordRequest/request.php';
require_once __DIR__.'/../../usecases/passwordRequest/reset.php';
require_once __DIR__.'/../../usecases/passwordRequest/renew.php';


class Controller
{
	static function signup($user, &$data_access, &$message_handler,  &$signup_view, &$presenter)
	{
		$inputData = new UserInputData($user, $data_access, $message_handler, $signup_view, $presenter);
		SignupInteractor::run($inputData);
	}

	static function confirm($confirm, &$data_access, &$confirm_view, &$presenter)
	{
		$confirmData = new ConfirmInputData($confirm, $data_access, $confirm_view, $presenter);
		ConfirmInteractor::run($confirmData);
	}

	static function login($credentials, &$data_access, &$login_view, &$presenter)
	{
		$loginData = new LoginInputData($credentials, $data_access, $login_view, $presenter);
		LoginInteractor::run($loginData);
	}

	static function upload($file, $dest, $filter, $userId, &$data_access, &$upload_view, &$presenter)
	{
		$uploadData = new UploadInputData($file, $dest, $filter, $userId, $data_access, $upload_view, $presenter);
		UploadInteractor::run($uploadData);
	}

	static function pswdRequest($email, &$data_access, &$message_handler, &$output_view, &$presenter)
	{
		$requestdata = new PswdRequestInputData($email, $data_access, $message_handler, $output_view, $presenter);
		PswdRequestInteractor::run($requestdata);
	}

	static function pswdReset($token, &$data_access, &$output_view, &$presenter)
	{
		$resetData = new PswdResetInputData($token, $data_access, $output_view, $presenter);
		PswdResetInteractor::run($resetData);
	}
	static function pswdRenew($user_id, $input, &$data_access, &$output_view,  &$presenter)
	{
		$renewData = new PswdRenewInputData($user_id, $input, $data_access, $output_view, $presenter);
		PswdRenewInteractor::run($renewData);
	}
}