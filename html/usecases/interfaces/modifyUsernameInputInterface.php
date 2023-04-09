<?php

require_once __DIR__.'/../data/modifyUsernameInputData.php';

interface ModifyUsernameInterface
{
	public static function run(ModifyUsernameInputData $modifydata);
	public static function check($modifydata, &$sessionUser);
}
