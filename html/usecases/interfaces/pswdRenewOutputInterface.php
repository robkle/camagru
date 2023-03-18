<?php

require_once __DIR__.'/../data/pswdRenewOutputData.php';

interface PswdRenewOutput
{
	public function pswdRenewOutput(PswdRenewStatus $status, PswdResetOutputData $session_user, PswdRenewViewModel $output_view);
}
