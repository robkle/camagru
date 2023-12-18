<?php

require_once __DIR__.'/../data/commentOutputData.php';

interface CommentOutput
{
	public function commentOutput(CommentStatus $status, CommentViewModel $output_view);
}
