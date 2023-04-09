<?php

require_once __DIR__.'/../data/modifyEmailOutputData.php';

interface modifyEmailOutput
{
	public function modifyEmailOutput(ModifyEmailStatus $status, ModifyEmailViewModel $output_view);
}
