<?php

require_once __DIR__.'/../interfaces/commentInputInterface.php';
require_once __DIR__.'/../interfaces/commentOutputInterface.php';
require_once __DIR__.'/../data/commentOutputData.php';

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
			return CommentStatus::CommentTooLong;
		}
		if (strlen($commentData->user_id) == 0) {
			return CommentStatus::Unauthorised;
		}
		$dbUser = $commentData->data_access->fetchUser($commentData->user_id, null, null);
		if ($dbUser === [NULL]) {
			return CommentStatus::SystemFailure;
		}
		if ($dbUser["id"] === null) {
			return CommentStatus::Unauthorised;
		}
		//2. get image owner with image id
		$dbImage = $commentData->data_access->fetchImageInfo($commentData->image_id);
		if ($dbImage === [NULL]) {
			return CommentStatus::SystemFailure;
		}
		if ($dbImage['id'] === null) {
			return CommentStatus::SystemFailure;
		}
		//3. store comment
		if ($commentData->data_access->postComment($commentData->image_id, $dbImage['user_id'], $commentData->comment) === false) {
			return CommentStatus::SystemFailure;
		}
		//4. email image user
		$imageUser = $commentData->data_access->fetchUser($dbImage['user_id'], null, null);
		if ($imageUser === [NULL]) {
			return CommentStatus::SystemFailure;
		}
		if ($imageUser["id"] === null) {
			return CommentStatus::SystemFailure;
		}
		$notificationData = new CommentOutputData($imageUser['login'], $dbUser['login'], $commentData->comment, $imageUser['email']);
		if ($commentData->message_handler->commentNotification($notificationData) === false) {
			return CommentStatus::SystemFailure;
		}
		
		return CommentStatus::Success;
	}
}
