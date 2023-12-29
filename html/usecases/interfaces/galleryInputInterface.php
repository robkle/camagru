<?php

require_once __DIR__.'/../data/galleryInputData.php';

interface GalleryInterface
{
	public static function run(GalleryInputData $galleryData);
	public static function check(GalleryInputData $galleryData, GalleryOutputDat  &$imageArray);
}
