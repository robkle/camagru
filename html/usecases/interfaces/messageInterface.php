<?php

interface MessageInterface
{
	public function signupEmail(SignupOutputData $info): bool;
}
