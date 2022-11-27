<?php

class ConfirmInputData
{
	public $ckey;
	public $data_access;
	public $output_view;
	public $presenter;
	

	function __construct($input, $data_access, $output_view, $presenter)
	{
		if (array_key_exists('ckey', $input))
		{
			$this->ckey = $input['ckey'];
		}
		$this->data_access = $data_access;
		$this->output_view = $output_view;
		$this->presenter = $presenter;
	}
}
