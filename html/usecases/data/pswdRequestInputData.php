<?php

class PswdRequestInputData
{
	public $email;
	public $data_access;
	public $message_handler;
	public $output_view;
	public $presenter;

	function __construct($email, $data_access, $message_handler, $output_view, $presenter)
	{
		$this->email = $email;
		$this->data_access = $data_access;
		$this->message_handler = $message_handler;
		$this->output_view = $output_view;
		$this->presenter = $presenter;
	}
}
