<?php

interface MessageInterface
{
	public function signupEmail(SignupOutputData $info): bool;
	public function pswdRequestEmail(PswdRequestOutputData $info) : bool;
	public function commentNotification(CommentOutputData $info) : bool;
}
