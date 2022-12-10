<?php

require_once __DIR__.'/../data/userInputData.php';

interface UserInteractor
{ 
	public static function run(UserInputData $userdata);
	public static function check(UserInputData $userdata);
}
