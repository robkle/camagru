<?php

class MockPswdResetViewModel
{
	public $err_msg;

	function create ($err_msg)
	{
		$this->err_msg = $err_msg;
	}
}
