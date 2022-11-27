<?php

require_once 'mockInput.php';
require_once 'controller.php';
require_once __DIR__ .'/../viewModels/mockSignupViewModel.php';
require_once __DIR__.'/../messageHandler/mockMessageHandler.php';
require_once __DIR__.'/../dataAccess/mockDataAccess.php';
require_once __DIR__.'/../presenter/mockSignupPresenter.php';

$data_access = new MockDataAccess();
$message_handler = new MockMessageHandler();
$signup_view = new MockSignupViewModel();
$presenter = new MockSignupPresenter();
Controller::signup($user, $data_access, $message_handler, $signup_view, $presenter);
