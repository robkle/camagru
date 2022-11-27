<?php

//confirmViewModel class with some placeholder variables and method

class MockConfirmViewModel
{
	public $err_msg;

	function create ($err_msg)
	{
		$this->err_msg = $err_msg;
	}
}
