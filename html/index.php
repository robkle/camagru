<?php


require_once __DIR__.'/controller/mockInput.php';
require_once __DIR__.'/controller/controller.php';
require_once __DIR__.'/dataAccess/mockDataAccess.php';
require_once __DIR__.'/viewModels/mockSignupViewModel.php';
require_once __DIR__.'/controller/mockConfirm.php';
require_once __DIR__.'/messageHandler/mockMessageHandler.php';
require_once __DIR__.'/presenter/mockSignupPresenter.php';
require_once __DIR__.'/viewModels/mockConfirmViewModel.php';
require_once __DIR__.'/presenter/mockConfirmPresenter.php';
//require __DIR__.'/viewModels/confirmViewModel.php';

$data_access = new MockDataAccess();
$message_handler = new MockMessageHandler();
$signup_view = new MockSignupViewModel();
$presenter = new MockSignupPresenter();
Controller::signup($user, $data_access, $message_handler, $signup_view, $presenter);
echo $signup_view->err_msg;

mockConfirm();



/*if (($handle = fopen("mockEmail.txt", "r")) !== FALSE)
{
	$line = fgets($handle);
	$confirm = ["ckey" => substr($line, 43)]; 
}
fclose($handle);
$confirm_view = new ConfirmViewModel();
Controller::confirm($confirm, $confirm_view);
echo $confirm_view->err_msg;*/
