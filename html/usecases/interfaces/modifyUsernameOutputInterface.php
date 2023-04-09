<?php

require_once __DIR__.'/../data/modifyUsernameOutputData.php';

interface modifyUsernameOutput
{
	public function modifyUsernameOutput(ModifyUsernameStatus $status, ModifyUsernameOutputData $session_user, ModifyUsernameViewModel $output_view);
}
