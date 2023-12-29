<?php

class ImageInputData
{
	public $image_id;
	public $data_access;
	public $output_view;
	public $presenter;

	function __construct($input, $data_access, $output_view, $presenter)
	{
		if ($input && array_key_exists('image_id', $input))
		{
			$this->image_id = $input['image_id'];
		}
		$this->data_access = $data_access;
		$this->output_view = $output_view;
		$this->presenter = $presenter;
	}
}
