<?php

require_once __DIR__.'/../../entities/outputStatus.php';

class UploadInputData
{
	public $userId; //_SESSION['userId']
//	public $fileName;
//	public $fileType;
//	public $fileSize;
	public $tmp_name;
	public $error;
	public $dest;
	public $filter;
	public $data_access;
	public $output_view;
	public $presenter;

	function __construct($file, $dest, $filter, $userId, $data_access, $output_view, $presenter)
	{
		//if (array_key_exists('name', $file)) {
		//	$this->fileName = $file['name'];
		//}
		//if (array_key_exists('type', $file)) {
		//	$this->fileType = $file['type'];
		//}
		//if (array_key_exists('size', $file)) {
		//	$this->fileSize = $file['size'];
		//}
		if (array_key_exists('tmp_name', $file)) {
			$this->tmpName = $file['tmp_name'];
		}
	
		if (array_key_exists('error', $file)) {
			$this->error = $file['error'];
		}
		$this->dest = $dest;
		$this->userId = $userId;
		$this->filter = $filter;
		$this->data_access = $data_access;
		$this->output_view = $output_view;
		$this->presenter = $presenter;
	} 
}

interface UploadInterface
{
	public static function run(UploadInputData $uploadData);
	public static function check(UploadInputData $uploadData);
} 

interface UploadOutput
{
	public function uploadOutput(Status $status, UploadViewModel $upload_view);
}

class UploadInteractor implements UploadInterface
{
	public static function run($uploadData)
	{
		$status = self::check($uploadData);
		if ($status == NULL) {
			$finalImage = self::merge($uploadData->tmpName, $uploadData->filter);
			if ($finalImage === FALSE){
				$status = Status::SystemFailure;
			} elseif (self::store($finalImage, $uploadData) === FALSE){
				$status = Status::SystemFailure;
			} else {
				$status = Status::Success;
			}
		}
		$uploadData->presenter->uploadOutput($status, $uploadData->output_view);
	}

	public static function check($uploadData)
	{
		if ($uploadData->userId === NULL) {
			return Status::InvalidUser;
		}

		$dbUser = $uploadData->data_access->fetchUser($uploadData->userId, null, null);
		if ($dbUser === [NULL]) {
			return Status::InvalidUser;
		}

		if ($uploadData->error != 0){
			return Status::UploadError;
		}

		if (isset($uploadData->tmpName) === FALSE){
			return Status::NoSource;
		}

		if ($uploadData->tmpName === NULL){
			return Status::NoSource;
		}
		
		if (mime_content_type($uploadData->tmpName) != "image/jpeg"){ 
			return Status::InvalidType;
		}

		if (filesize($uploadData->tmpName) > (1024 * 1024)){
			return Status::SizeLimit;
		}

		if ($uploadData->dest === NULL){
			return Status::NoDestination;
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
