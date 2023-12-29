<?php

require_once __DIR__.'/../data/imageInputData.php';

interface ImageInterface
{
	public static function run(ImageInputData $inputData);
	public static function check(ImageInputData $inputData, ImageOutputData &$imageData);
}
