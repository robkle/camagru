<?php

require_once __DIR__.'/../data/likeInputData.php';

interface LikeInterface
{
	public static function run(LikeInputData $likeData);
	public static function check(LikeInputData $likeData);
}
