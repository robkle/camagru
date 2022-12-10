<?php

require_once __DIR__.'/../data/loginInputData.php';

interface LoginInterface
{
	public static function run(LoginInputData $logindata);
	public static function check($loginData, $dbUser, &$sessionUser);
}
