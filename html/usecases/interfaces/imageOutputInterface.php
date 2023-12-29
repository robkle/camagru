<?php

require_once __DIR__.'/../data/imageOutputData.php';

interface ImageOutput
{
	public function ImageOutput(ImageStatus $status, ImageOutputData $image, ImageViewModel $output_view);
}
