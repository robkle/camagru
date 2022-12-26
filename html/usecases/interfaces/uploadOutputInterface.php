<?php

require_once __DIR__.'/../data/uploadOutputData.php';

interface UploadOutput
{
	public function uploadOutput(UploadStatus $status, UploadViewModel $upload_view);
}
