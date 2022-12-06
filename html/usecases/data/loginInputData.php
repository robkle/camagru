<?php

class LoginInputData
{
	public $login;
	public $pswd;
	public $data_access;
	public $output_view;
	public $presenter;

	function __construct($input, $data_access, $output_view, $presenter)
	{
		if (array_key_exists('login', $input)) {
			$this->login = $input['login'];
		}
		if (array_key_exists('pswd', $input)) {
			$this->pswd = $input['pswd'];
		}
		$this->data_access = $data_access;
		$this->output_view = $output_view;
		$this->presenter = $presenter;
	}
}
