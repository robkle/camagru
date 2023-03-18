<?php

require_once __DIR__.'/../data/pswdRenewInputData.php';

interface PswdRenewInterface
{
	public static function run(PswdRenewInputData $input);
	public static function check(PswdRenewInputData $input);
}
