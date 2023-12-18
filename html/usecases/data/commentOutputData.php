<?php

enum CommentStatus
{
	case Success;
	case Unauthorised;
	case CommentTooLong;
	case SystemFailure;
}

class CommentOutputData
{
	public $recipient;
	public $commentor;
	public $comment;
	public $email;

	function __construct($recipient, $commentor, $comment, $email)
	{
		$this->recipient = $recipient;
		$this->commentor = $commentor;
		$this->comment = $comment;
		$this->email = $email;
	}
}

