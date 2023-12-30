<?php

require_once __DIR__.'/../data/removeOutputData.php';

interface RemoveOutput
{
	public function removeOutput(RemoveStatus $status, RemoveViewModel $remove_view);
}
