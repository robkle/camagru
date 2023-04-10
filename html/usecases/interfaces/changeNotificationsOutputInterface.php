<?php

require_once __DIR__.'/../data/changeNotificationsOutputData.php';

interface changeNotificationsOutput
{
	public function changeNotificationsOutput(ChangeNotificationsStatus $status, ChangeNotificationsViewModel $output_view);
}
