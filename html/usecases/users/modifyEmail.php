<?php

require_once __DIR__.'/../../entities/outputStatus.php';

class ModifyEmailInputData
{
	public $user_id;
	public $email;
	public $data_access;
	public $output_view;
	public $presenter;
	

	function __construct($user_id, $input, $data_access, $output_view, $presenter)
	{
		if ($input && array_key_exists('email', $input))
		{
			$this->email = $input['email'];
		}
		$this->user_id = $user_id;
		$this->data_access = $data_access;
		$this->output_view = $output_view;
		$this->presenter = $presenter;
	}
}

interface ModifyEmailInterface
{
	public static function run(ModifyEmailInputData $modifydata);
	public static function check(ModifyEmailInputData $modifydata);
}

interface modifyEmailOutput
{
	public function modifyEmailOutput(ModifyEmailStatus $status, ModifyEmailViewModel $output_view);
}

class ModifyEmailInteractor implements ModifyEmailInterface
{
	public static function run($modifydata)
	{
		$status = ModifyEmailInteractor::check($modifydata);
		$modifydata->presenter->modifyEmailOutput($status, $modifydata->output_view);
	}

	public static function check($modifydata)
	{
		if (strlen($modifydata->user_id) == 0) {
			return Status::Unauthorised;
		}
		if (filter_var($modifydata->email, FILTER_VALIDATE_EMAIL) === false) {
			return Status::InvalidEmail;
		}
		$dbUser = $modifydata->data_access->fetchUser($modifydata->user_id, null, null);
		if ($dbUser === [NULL]) {
			return Status::SystemFailure;
		}
		if ($dbUser['id'] === null) {
			return Status::Unauthorised;
		}
		if ($modifydata->data_access->changeEmail($modifydata->user_id, $modifydata->email)!== TRUE) {
			return Status::SystemFailure;
		}
		return Status::Success;
	}
}
