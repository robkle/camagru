<?php

require_once __DIR__.'/../data/uploadInputData.php';

interface UploadInterface
{
	public static function run(UploadInputData $uploadData);
	public static function check(UploadInputData $uploadData);
} 
