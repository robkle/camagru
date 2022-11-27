<?php

class UserInputData
{
	public $login;
	public $email;
	public $pswd;
	public $pswd2;
	public $data_access;
	public $message_handler;
	public $output_view;
	public $presenter;

	function __construct($input, $data_access,  $message_handler, $output_view, $presenter)
	{
		if (array_key_exists('login', $input))
		{
			$this->login = $input['login'];
		}
		if (array_key_exists('email', $input))
		{
			$this->email = $input['email'];
		}
		if (array_key_exists('pswd', $input))
		{
			$this->pswd = $input['pswd'];
		}
		if (array_key_exists('pswd2', $input))
		{
			$this->pswd2 = $input['pswd2'];
		}
		$this->data_access = $data_access;
		$this->message_handler = $message_handler;
		$this->output_view = $output_view;
		$this->presenter = $presenter;
	}
}
