<?php

require_once __DIR__.'/../usecases/data/userInputData.php';
require_once __DIR__.'/../usecases/data/confirmInputData.php';
require_once __DIR__.'/../usecases/signup/signup.php';
require_once __DIR__.'/../usecases/signup/confirm.php';

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
	
}
