<?php

class CommentInputData
{
	public $user_id;
	public $image_id;
	public $comment;
	public $message_handler;
	public $data_access;
	public $output_view;
	public $presenter;

	function __construct($user_id, $input, $data_access, $message_handler, $output_view, $presenter)
	{
		if ($input && array_key_exists('image_id', $input))
		{
			$this->image_id = $input['image_id'];
		}
		if ($input && array_key_exists('comment', $input))
		{
			$this->comment = $input['comment'];
		}
		$this->user_id = $user_id;
		$this->data_access = $data_access;
		$this->message_handler = $message_handler;
		$this->output_view = $output_view;
		$this->presenter = $presenter;
	}
}
