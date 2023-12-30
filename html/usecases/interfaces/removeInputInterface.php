<?php

require_once __DIR__.'/../data/removeInputData.php';

interface RemoveInterface
{
	public static function run(RemoveInputData $removeData);
	public static function check(RemoveInputData $removeData);
} 
