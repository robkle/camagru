<?php

class UploadInputData
{
	public $userId; //_SESSION['userId']
//	public $fileName;
//	public $fileType;
//	public $fileSize;
	public $tmp_name;
	public $error;
	public $filter;
	public $data_access;
	public $output_view;
	public $presenter;

	function __construct($file, $filter, $userId, $data_access, $output_view, $presenter)
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
		$this->userId = $userId;
		$this->filter = $filter;
		$this->data_access = $data_access;
		$this->output_view = $output_view;
		$this->presenter = $presenter;
	} 
}
