<?php

require_once __DIR__.'/controller.php';
require_once __DIR__.'/../viewModels/mockConfirmViewModel.php';
require_once __DIR__.'/../dataAccess/mockDataAccess.php';
require_once __DIR__.'/../presenter/mockConfirmPresenter.php';

function mockConfirm () {
	if (($handle = fopen("mockEmail.txt", "r")) !== FALSE)
	{
		$line = fgets($handle);
		$confirm = ["ckey" => substr($line, 43)]; 
	}
	fclose($handle);
	$data_access = new MockDataAccess();
	$confirm_view = new MockConfirmViewModel();
	$presenter = new MockConfirmPresenter();
	Controller::confirm($confirm, $data_access, $confirm_view, $presenter);
	echo $confirm_view->err_msg;
}
