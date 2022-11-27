<?php

require_once __DIR__.'/../data/userOutputData.php';

interface UserOutput
{
	public function signupOutput(SignupStatus $status, SignupViewModel $signup_view);
}
