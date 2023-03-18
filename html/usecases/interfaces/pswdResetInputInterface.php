<?php

require_once __DIR__.'/../data/pswdResetInputData.php';

interface PswdResetInterface
{
	public static function run(PswdResetInputData $resetdata);
	public static function check(PswdResetInputData $resetdata, PswdResetOutputData &$sessionUser);
}
