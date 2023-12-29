<?php

enum LikeStatus
{
	case Success;
	case Unauthorised;
	case SystemFailure;
}

class LikeOutputData
{
	public $recipient;
	public $liker;
	public $email;

	function __construct($recipient, $liker, $email)
	{
		$this->recipient = $recipient;
		$this->commentor = $liker;
		$this->email = $email;
	}
}

