<?php

class PswdResetInputData
{
	public $token;
	public $data_access;
	public $output_view;
	public $presenter;
	

	function __construct($input, $data_access, $output_view, $presenter)
	{
		if ($input && array_key_exists('token', $input))
		{
			$this->token = $input['token'];
		}
		$this->data_access = $data_access;
		$this->output_view = $output_view;
		$this->presenter = $presenter;
	}
}
