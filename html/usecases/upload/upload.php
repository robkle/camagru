<?php

require_once __DIR__.'/../interfaces/uploadInputInterface.php';

class UploadInteractor implements UploadInterface
{
	public static function run($uploadData)
	{
		$status = self::check($uploadData);
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
		return UploadStatus::Success;
	}
}
