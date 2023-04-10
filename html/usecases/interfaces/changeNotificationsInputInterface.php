<?php

require_once __DIR__.'/../data/changeNotificationsInputData.php';

interface ChangeNotificationsInterface
{
	public static function run(ChangeNotificationsInputData $modifydata);
	public static function check(ChangeNotificationsInputData $modifydata);
}
