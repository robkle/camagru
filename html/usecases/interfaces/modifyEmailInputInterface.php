<?php

require_once __DIR__.'/../data/modifyEmailInputData.php';

interface ModifyEmailInterface
{
	public static function run(ModifyEmailInputData $modifydata);
	public static function check(ModifyEmailInputData $modifydata);
}
