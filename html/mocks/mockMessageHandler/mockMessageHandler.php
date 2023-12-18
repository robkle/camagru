<?php

require_once __DIR__.'/../../usecases/interfaces/messageInterface.php';

class MockMessageHandler implements MessageInterface
{
	public function signupEmail(SignupOutputData $info) : bool
	{
		$SUCCESS = false;
		$body = "http://127.0.0.1:8080/mockConfirm.php?ckey=$info->ckey";
		if (($handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockEmail/mockEmail.txt", "w")) !== FALSE)
		{
			if (fwrite($handle, $body) !== FALSE)
			{
				$SUCCESS = true;
			}
		}
		fclose($handle);
		return $SUCCESS;

	}
	
	public function pswdRequestEmail(PswdRequestOutputData $info) : bool
	{
		$SUCCESS = false;
		$body = "http://127.0.0.1.8080/mockRequest.php?$info->token";
		if (($handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockEmail/mockRequest.txt", "w")) !== false)
		{
			if (fwrite($handle, $body) !== false)
			{
				$SUCCESS = true;
			}
		}
		fclose($handle);
		return $SUCCESS;
	}

	public function commentNotification(CommentOutputData $info) : bool
	{
		$SUCCESS = false;
		$body = "Hi $info->recipient!\n$info->commentor commented on your image:\n\"$info->comment\"\n";
		if (($handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockEmail/mockComments.txt", "a")) !== false)
		{
			if (fwrite($handle, $body) !== false)
			{
				$SUCCESS = true;
			}
		}
		fclose($handle);
		return $SUCCESS;
	}

	public function likeNotification(LikeOutputData $info) : bool
	{
		$SUCCESS = false;
		$body = "Hi $info->recipient!\n$info->liker liked your image.\n";
		if (($handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockEmail/mockLikes.txt", "a")) !== false)
		{
			if (fwrite($handle, $body) !== false)
			{
				$SUCCESS = true;
			}
		}
		fclose($handle);
		return $SUCCESS;
	}
}
