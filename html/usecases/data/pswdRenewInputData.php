<?php

class PswdRenewInputData
{
	public $user_id;
	public $pswd;
	public $pswd2;
	public $data_access;
	public $output_view;
	public $presenter;
	

	function __construct($user_id, $input, $data_access, $output_view, $presenter)
	{
		if ($input && array_key_exists('pswd', $input))
		{
			$this->pswd = $input['pswd'];
		}
		if ($input && array_key_exists('pswd2', $input))
		{
			$this->pswd2 = $input['pswd2'];
		}
		$this->user_id = $user_id;
		$this->data_access = $data_access;
		$this->output_view = $output_view;
		$this->presenter = $presenter;
	}
}