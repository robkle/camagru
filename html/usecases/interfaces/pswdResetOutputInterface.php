<?php

require_once __DIR__.'/../data/pswdResetOutputData.php';

interface PswdResetOutput
{
	public function pswdResetOutput(PswdResetStatus $status, PswdResetOutputData $session_user, PswdResetViewModel $output_view);
}
