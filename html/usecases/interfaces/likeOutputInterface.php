<?php

require_once __DIR__.'/../data/likeOutputData.php';

interface LikeOutput
{
	public function likeOutput(LikeStatus $status, LikeViewModel $output_view);
}
