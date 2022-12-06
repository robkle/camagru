<?php

require_once __DIR__.'/../data/loginOutputData.php';

interface LoginOutput
{
	public function loginOutput(LoginStatus $status, LoginOutputData $session_user, LoginViewModel $login_view);
}
