<?php

require_once __DIR__.'/../interfaces/uploadInputInterface.php';

class UploadInteractor implements UploadInterface
{
	public static function run($uploadData)
	{
		$status = self::check($uploadData);
		if ($status == NULL) {
			$finalImage = self::merge($uploadData->tmpName, $uploadData->filter);
			if ($finalImage === FALSE){
				$status = UploadStatus::SystemFailure;
			} elseif (self::store($finalImage, $uploadData) === FALSE){
				$status = UploadStatus::SystemFailure;
			} else {
				$status = UploadStatus::Success;
			}
		}
		$uploadData->presenter->uploadOutput($status, $uploadData->output_view);
	}

	public static function check($uploadData)
	{
		if ($uploadData->userId === NULL) {
			return UploadStatus::InvalidUser;
		}

		$dbUser = $uploadData->data_access->fetchUser($uploadData->userId, null, null);
		if ($dbUser === [NULL]) {
			return UploadStatus::InvalidUser;
		}

		if ($uploadData->error != 0){
			return UploadStatus::UploadError;
		}

		if (isset($uploadData->tmpName) === FALSE){
			return UploadStatus::NoSource;
		}

		if ($uploadData->tmpName === NULL){
			return UploadStatus::NoSource;
		}
		
		if (mime_content_type($uploadData->tmpName) != "image/jpeg"){ 
			return UploadStatus::InvalidType;
		}

		if (filesize($uploadData->tmpName) > (1024 * 1024)){
			return UploadStatus::SizeLimit;
		}

		if ($uploadData->dest === NULL){
			return UploadStatus::NoDestination;
		}
		
		return NULL;
	}
	
	public static function merge($tmpName, $filter)
	{
		//implement merging image and filter here
		$to = "/tmp/" . uniqid() . ".jpg";
		copy($tmpName, $to); 
		return $to;
	}

	public static function store($image, $uploadData)
	{
		$imageName = $uploadData->userId . time() . ".jpg";
		$imagePath = $uploadData->dest . $imageName;
		if (rename($image, $imagePath) === FALSE){

			return FALSE;
		}
		if ($uploadData->data_access->postImage($uploadData->userId, $imageName) === FALSE){
			return FALSE;
		}
		return TRUE;
	}
}
