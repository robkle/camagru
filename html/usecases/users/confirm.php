<?php

require_once __DIR__.'/../../entities/outputStatus.php';

class ConfirmInputData
{
	public $ckey;
	public $data_access;
	public $output_view;
	public $presenter;
	

	function __construct($input, $data_access, $output_view, $presenter)
	{
		if ($input && array_key_exists('ckey', $input))
		{
			$this->ckey = $input['ckey'];
		}
		$this->data_access = $data_access;
		$this->output_view = $output_view;
		$this->presenter = $presenter;
	}
}

interface confirmOutput
{
	public static function confirmOutput(Status $status, ConfirmViewModel $output_view);
}

interface ConfirmUserInteractor
{
	public static function run(ConfirmInputData $userdata);
	public static function check(ConfirmInputData $userdata);
}

class ConfirmInteractor implements ConfirmUserInteractor
{
	public static function run($userdata)
	{
		$status = self::check($userdata);
		$userdata->presenter->confirmOutput($status, $userdata->output_view);
	}

	public static function check($userdata)
	{
		if ($userdata->ckey == null) {
			return Status::QueryInvalid;
		}
		$db_ckey = $userdata->data_access->fetchCkey($userdata->ckey);
		if ($db_ckey === []) {
			return Status::SystemFailure;
		}	
		if (isset($db_ckey['ckey']) !== true) {
			return Status::AccountInvalid;
		}
		if ($db_ckey['confirm'] === "Yes") {
			return Status::AccountConfirmed;
		}
		if ($userdata->data_access->confirmUser($userdata->ckey) !== TRUE) {
			return Status::SystemFailure;
		}
		return Status::Success;
	}
}
