<?php

require_once __DIR__.'/../interfaces/imageInputInterface.php';
require_once __DIR__.'/../interfaces/imageOutputInterface.php';
require_once __DIR__.'/../data/imageOutputData.php';

class ImageInteractor implements ImageInterface
{
	public static function run($inputData)
	{
		$imageData = new ImageOutputData(array(), array());
		$status = self::check($inputData, $imageData);
		$inputData->presenter->imageOutput($status, $imageData, $inputData->output_view);
	}

	public static function check($inputData, &$imageData)
	{
		$image = $inputData->data_access->fetchImage($inputData->image_id);
		if ($image == [NULL]) {
			return ImageStatus::SystemFailure;
		}
		$comments = $inputData->data_access->fetchComments($inputData->image_id);
		$imageData->image = $image;
		$imageData->comments = $comments;
		return ImageStatus::Success;
	}
}
