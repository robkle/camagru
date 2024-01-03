<?php

require_once __DIR__.'/../../entities/outputStatus.php';

class ImageInputData
{
	public $image_id;
	public $data_access;
	public $output_view;
	public $presenter;

	function __construct($input, $data_access, $output_view, $presenter)
	{
		if ($input && array_key_exists('image_id', $input))
		{
			$this->image_id = $input['image_id'];
		}
		$this->data_access = $data_access;
		$this->output_view = $output_view;
		$this->presenter = $presenter;
	}
}

interface ImageInterface
{
	public static function run(ImageInputData $inputData);
	public static function check(ImageInputData $inputData, ImageOutputData &$imageData);
}

class ImageOutputData
{
	public $image = array();
	public $comments = array();

	function __construct($image, $comments)
	{
		$this->image = $image;
		$this->comments = $comments;
	}
}

interface ImageOutput
{
	public function ImageOutput(Status $status, ImageOutputData $image, ImageViewModel $output_view);
}

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
		if ($image == []) {
			return Status::SystemFailure;
		}
		$comments = $inputData->data_access->fetchComments($inputData->image_id);
		$imageData->image = $image;
		$imageData->comments = $comments;
		return Status::Success;
	}
}
