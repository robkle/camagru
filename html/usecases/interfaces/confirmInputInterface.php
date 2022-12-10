<?php

require_once __DIR__.'/../data/confirmInputData.php';

interface ConfirmUserInteractor
{
	public static function run(ConfirmInputData $userdata);
	public static function check(ConfirmInputData $userdata);
}
