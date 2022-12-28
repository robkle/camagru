<?php

require_once __DIR__.'/../data/pswdRequestInputData.php';

interface PswdRequestInterface
{
		public static function run(PswdRequestInputData $requestdata);
		public static function check(PswdRequestInputData $requestdata);
}
