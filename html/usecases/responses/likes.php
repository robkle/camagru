<?php

require_once __DIR__.'/../../entities/outputStatus.php';

class LikeInputData
{
	public $user_id;
	public $image_id;
	public $message_handler;
	public $data_access;
	public $output_view;
	public $presenter;

	function __construct($user_id, $input, $data_access, $message_handler, $output_view, $presenter)
	{
		if ($input && array_key_exists('image_id', $input))
		{
			$this->image_id = $input['image_id'];
		}
		$this->user_id = $user_id;
		$this->data_access = $data_access;
		$this->message_handler = $message_handler;
		$this->output_view = $output_view;
		$this->presenter = $presenter;
	}
}

interface LikeInterface
{
	public static function run(LikeInputData $likeData);
	public static function check(LikeInputData $likeData);
}

class LikeOutputData
{
	public $recipient;
	public $liker;
	public $email;

	function __construct($recipient, $liker, $email)
	{
		$this->recipient = $recipient;
		$this->commentor = $liker;
		$this->email = $email;
	}
}

interface LikeOutput
{
	public function likeOutput(Status $status, LikeViewModel $output_view);
}

class LikeInteractor implements LikeInterface
{
	public static function run($likeData)
	{
		$status = self::check($likeData);
		$likeData->presenter->likeOutput($status, $likeData->output_view);
	}

	public static function check($likeData)
	{
		if (strlen($likeData->user_id) == 0) {
			return Status::Unauthorised;
		}
		$dbUser = $likeData->data_access->fetchUser($likeData->user_id, null, null);
		if ($dbUser === []) {
			return Status::SystemFailure;
		}
		if ($dbUser["id"] === null) {
			return Status::Unauthorised;
		}
		//2. get image owner with image id
		$dbImage = $likeData->data_access->fetchImageInfo($likeData->image_id);
		if ($dbImage === []) {
			return Status::SystemFailure;
		}
		if ($dbImage['id'] === null) {
			return Status::SystemFailure;
		}
		//3. create/remove like
		$like = $likeData->data_access->fetchLike($likeData->image_id, $likeData->user_id);
		if ($like === []) {
			return Status::SystemFailure;
		}
		$SUCCESS = false;
		$addLike = false;
		if ($like["like_id"] === null) {
			$SUCCESS=$likeData->data_access->addLike($likeData->image_id, $likeData->user_id);
			$addLike = true;
		} else {
			$SUCCESS = $likeData->data_access->removeLike($likeData->image_id, $likeData->user_id);
		}
		if ($SUCCESS === false) {
			return Status::SystemFailure;
		}
		
		//4. email image user
		if ($addLike) {
			$imageUser = $likeData->data_access->fetchUser($dbImage['user_id'], null, null);
			if ($imageUser === []) {
				return Status::SystemFailure;
			}
			if ($imageUser["id"] === null) {
				return Status::SystemFailure;
			}
			$notificationData = new LikeOutputData($imageUser['login'], $dbUser['login'], $imageUser['email']);
			if ($likeData->message_handler->likeNotification($notificationData) === false) {
				return Status::SystemFailure;
			}
		}
		return Status::Success;
	}
}
