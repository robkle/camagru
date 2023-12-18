<?php

require_once __DIR__.'/../data/commentInputData.php';

interface CommentInterface
{
	public static function run(CommentInputData $commentData);
	public static function check($commentData);
}
