<?php

require_once __DIR__.'/../../entities/outputStatus.php';

class GalleryInputData
{
	public $user_id;
	public $creator;
	public $data_access;
	public $output_view;
	public $presenter;

	function __construct($user_id, $input, $data_access, $output_view, $presenter)
	{
		if ($input && array_key_exists('creator', $input))
		{
			$this->creator = $input['creator'];
		}
		$this->user_id = $user_id;
		$this->data_access = $data_access;
		$this->output_view = $output_view;
		$this->presenter = $presenter;
	}
}

interface GalleryInterface
{
	public static function run(GalleryInputData $galleryData);
	public static function check(GalleryInputData $galleryData, GalleryOutputDat  &$imageArray);
}

class GalleryOutputData
{
	public $gallery;

	function __construct($gallery)
	{
		$this->gallery = $gallery;
	}
}

interface GalleryOutput
{
	public function galleryOutput(Status $status, GalleryOutputData $gallery, GalleryViewModel $output_view);
}

class GalleryInteractor implements GalleryInterface
{
	public static function run($galleryData)
	{
		$imageArray = new GalleryOutputData(array());
		$status = self::check($galleryData, $imageArray);
		$galleryData->presenter->galleryOutput($status, $imageArray, $galleryData->output_view);
	}

	public static function check($galleryData, &$imageArray)
	{
		$images = $galleryData->data_access->fetchImages($galleryData->creator);
		if ($images === NULL) {
			return Status::SystemFailure;
		}
		$imageArray->gallery = $images;
		return Status::Success;
	}
}

class PrivateGalleryInteractor extends GalleryInteractor
{
	public static function run($galleryData)
	{
		if (empty($galleryData->user_id)) {
			$imageArray = new GalleryOutputData(array());
			$galleryData->presenter->galleryOutput(Status::Unauthorised, $imageArray, $galleryData->output_view);
		} else {
			$galleryData->creator = $galleryData->user_id;
			parent::run($galleryData);
		}
	}
}
