<?php

require_once __DIR__.'/../../entities/outputStatus.php';

class CommentInputData
{
	public $user_id;
	public $image_id;
	public $comment;
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
		if ($input && array_key_exists('comment', $input))
		{
			$this->comment = $input['comment'];
		}
		$this->user_id = $user_id;
		$this->data_access = $data_access;
		$this->message_handler = $message_handler;
		$this->output_view = $output_view;
		$this->presenter = $presenter;
	}
}

interface CommentInterface
{
	public static function run(CommentInputData $commentData);
	public static function check($commentData);
}

class CommentOutputData
{
	public $recipient;
	public $commentor;
	public $comment;
	public $email;

	function __construct($recipient, $commentor, $comment, $email)
	{
		$this->recipient = $recipient;
		$this->commentor = $commentor;
		$this->comment = $comment;
		$this->email = $email;
	}
}

interface CommentOutput
{
	public function commentOutput(Status $status, CommentViewModel $output_view);
}

class CommentInteractor implements CommentInterface
{
	public static function run($commentData)
	{
		$status = self::check($commentData);
		$commentData->presenter->commentOutput($status, $commentData->output_view);
	}

	public static function check($commentData)
	{
		if (strlen($commentData->comment) > 256) {
			return Status::CommentTooLong;
		}
		if (strlen($commentData->user_id) == 0) {
			return Status::Unauthorised;
		}
		$dbUser = $commentData->data_access->fetchUser($commentData->user_id, null, null);
		if ($dbUser === [NULL]) {
			return Status::SystemFailure;
		}
		if ($dbUser["id"] === null) {
			return Status::Unauthorised;
		}
		//2. get image owner with image id
		$dbImage = $commentData->data_access->fetchImageInfo($commentData->image_id);
		if ($dbImage === [NULL]) {
			return Status::SystemFailure;
		}
		if ($dbImage['id'] === null) {
			return Status::SystemFailure;
		}
		//3. store comment
		if ($commentData->data_access->postComment($commentData->image_id, $dbImage['user_id'], $commentData->comment) === false) {
			return Status::SystemFailure;
		}
		//4. email image user
		$imageUser = $commentData->data_access->fetchUser($dbImage['user_id'], null, null);
		if ($imageUser === [NULL]) {
			return Status::SystemFailure;
		}
		if ($imageUser["id"] === null) {
			return Status::SystemFailure;
		}
		$notificationData = new CommentOutputData($imageUser['login'], $dbUser['login'], $commentData->comment, $imageUser['email']);
		if ($commentData->message_handler->commentNotification($notificationData) === false) {
			return Status::SystemFailure;
		}
		
		return Status::Success;
	}
}
