<?php

require_once __DIR__.'/../interfaces/likeInputInterface.php';
require_once __DIR__.'/../interfaces/likeOutputInterface.php';
require_once __DIR__.'/../data/likeOutputData.php';

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
			return LikeStatus::Unauthorised;
		}
		$dbUser = $likeData->data_access->fetchUser($likeData->user_id, null, null);
		if ($dbUser === [NULL]) {
			return LikeStatus::SystemFailure;
		}
		if ($dbUser["id"] === null) {
			return LikeStatus::Unauthorised;
		}
		//2. get image owner with image id
		$dbImage = $likeData->data_access->fetchImageInfo($likeData->image_id);
		if ($dbImage === [NULL]) {
			return LikeStatus::SystemFailure;
		}
		if ($dbImage['id'] === null) {
			return LikeStatus::SystemFailure;
		}
		//3. create/remove like
		$like = $likeData->data_access->fetchLike($likeData->image_id, $likeData->user_id);
		if ($like === [NULL]) {
			return LikeStatus::SystemFailure;
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
			return LikeStatus::SystemFailure;
		}
		
		//4. email image user
		if ($addLike) {
			$imageUser = $likeData->data_access->fetchUser($dbImage['user_id'], null, null);
			if ($imageUser === [NULL]) {
				return LikeStatus::SystemFailure;
			}
			if ($imageUser["id"] === null) {
				return LikeStatus::SystemFailure;
			}
			$notificationData = new LikeOutputData($imageUser['login'], $dbUser['login'], $imageUser['email']);
			if ($likeData->message_handler->likeNotification($notificationData) === false) {
				return LikeStatus::SystemFailure;
			}
		}
		return LikeStatus::Success;
	}
}
