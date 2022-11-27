
<?php

require_once __DIR__.'/../data/confirmOutputData.php';

interface confirmOutput
{
	public static function confirmOutput(ConfirmStatus $status, ConfirmViewModel $output_view);
}
