<?php

require_once __DIR__.'/../data/pswdRequestOutputData.php';

interface PswdRequestOutput
{
	public function pswdRequestOutput(PswdRequestStatus $status, PswdRequestViewModel $pswdRequest_view);
}
