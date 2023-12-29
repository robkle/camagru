<?php

require_once __DIR__.'/../interfaces/galleryInputInterface.php';
require_once __DIR__.'/../interfaces/galleryOutputInterface.php';
require_once __DIR__.'/../data/galleryOutputData.php';

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
			return GalleryStatus::SystemFailure;
		}
		$imageArray->gallery = $images;
		return GalleryStatus::Success;
	}
}

class PrivateGalleryInteractor extends GalleryInteractor
{
	public static function run($galleryData)
	{
		if (empty($galleryData->user_id)) {
			$imageArray = new GalleryOutputData(array());
			$galleryData->presenter->galleryOutput(GalleryStatus::Unauthorized, $imageArray, $galleryData->output_view);
		} else {
			$galleryData->creator = $galleryData->user_id;
			parent::run($galleryData);
		}
	}
}
