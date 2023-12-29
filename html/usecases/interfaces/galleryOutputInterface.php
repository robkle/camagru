<?php

require_once __DIR__.'/../data/galleryOutputData.php';

interface GalleryOutput
{
	public function galleryOutput(GalleryStatus $status, GalleryOutputData $gallery, GalleryViewModel $output_view);
}
