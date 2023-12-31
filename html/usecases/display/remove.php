<?php

require_once __DIR__.'/../../entities/outputStatus.php';

class RemoveInputData
{
	public $user_id;
	public $image_id;
	public $data_access;
	public $output_view;
	public $presenter;

	function __construct($user_id, $input, $data_access, $output_view, $presenter)
	{
		if ($input && array_key_exists('image_id', $input))
		{
			$this->image_id = $input['image_id'];
		}
		$this->user_id = $user_id;
		$this->data_access = $data_access;
		$this->output_view = $output_view;
		$this->presenter = $presenter;
	}
}

interface RemoveInterface
{
	public static function run(RemoveInputData $removeData);
	public static function check(RemoveInputData $removeData);
} 

interface RemoveOutput
{
	public function removeOutput(RemoveStatus $status, RemoveViewModel $remove_view);
}

class RemoveInteractor implements RemoveInterface
{
	public static function run($removeData)
	{
		$status = self::check($removeData);
		$removeData->presenter->removeOutput($status, $removeData->output_view);
	}

	public static function check($removeData)
	{
		if (empty($removeData->user_id)) {
			return Status::Unauthorised;
		}
		$dbuser = $removeData->data_access->fetchUser($removeData->user_id, null, null);
		if ($dbuser === [NULL]) {
			return Status::SystemFailure;
		}
		if ($dbuser["id"] == null) {
			return Status::Unauthorised;
		}
		if ($removeData->data_access->removeImage($removeData->image_id, $removeData->user_id) === FALSE) {
			return Status::SystemFailure;
		}
		return Status::Success;
	}
}
