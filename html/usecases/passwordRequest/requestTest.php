<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__.'/../../mocks/mockDataAccess/mockDataAccess.php';
require_once __DIR__.'/../../mocks/mockMessageHandler/mockMessageHandler.php';
require_once __DIR__.'/../../mocks/mockViewModels/mockPswdRequestViewModel.php';
require_once __DIR__.'/../../mocks/mockPresenter/mockPswdRequestPresenter.php';
require_once __DIR__.'/../../mocks/mockController/mockController.php';

final class requestTest extends TestCase
{	
	public function createUser($login, $email, $pswd, $confirmed)
	{
		$pswd_encrypt = password_hash($pswd, PASSWORD_DEFAULT, ['cost'=>12]);
		$user = [1000, $login, $email, $pswd_encrypt, $confirmed, "12345", "On"];
		$handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv", "w");
		fputcsv($handle, $user);
		fclose($handle);
	}

	public function clearDb() :void
	{
		$usersfile=fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv", "w");
		fclose($usersfile); 
		$usersfile=fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/pswdRequest.csv", "w");
		fclose($usersfile); 
	}

	public function testSuccess()
	{	
		$this->createUser("username", "user@domain.com", "#Qwerty12345!", "Yes");
		$data_access = new MockDataAccess();
		$message_handler = new MockMessageHandler();
		$output_view = new MockPswdRequestViewModel();
		$presenter = new MockPswdRequestPresenter();
		Controller::pswdRequest("user@domain.com", $data_access, $message_handler, $output_view, $presenter);
		$this->assertSame("Success", $output_view->err_msg);
	}

	public function testInvalidEmail()
	{	
		$data_access = new MockDataAccess();
		$message_handler = new MockMessageHandler();
		$output_view = new MockPswdRequestViewModel();
		$presenter = new MockPswdRequestPresenter();
		Controller::pswdRequest("invalid@email.com", $data_access, $message_handler, $output_view, $presenter);
		$this->assertSame("InvalidEmail", $output_view->err_msg);
	}

	public function testDbFetchFail()
	{
		$data_access = $this->createStub(MockDataAccess::class);
		$data_access->method('fetchUser')->will($this->returnValue([NULL]));
		$message_handler = new MockMessageHandler();
		$output_view = new MockPswdRequestViewModel();
		$presenter = new MockPswdRequestPresenter();
		Controller::pswdRequest("user@domain.com", $data_access, $message_handler, $output_view, $presenter);
		$this->assertSame("SystemFailure", $output_view->err_msg);
	}

	public function testMessageFail()
	{
		$data_access = new MockDataAccess();
		$message_handler = $this->createStub(MockMessageHandler::class);
		$message_handler->method('pswdRequestEmail')->will($this->returnValue(False));
		$output_view = new MockPswdRequestViewModel();
		$presenter = new MockPswdRequestPresenter();
		Controller::pswdRequest("user@domain.com", $data_access, $message_handler, $output_view, $presenter);
		$this->assertSame("SystemFailure", $output_view->err_msg);
	}

	public function testDeleteTokenFail()
	{
		$data_access = $this->createStub(MockDataAccess::class);
		$data_access->method('fetchUser')->will($this->returnValue(["email" => "user@domain.com"]));
		$data_access->method('deleteRequestToken')->will($this->returnValue(False));
		$message_handler = new MockMessageHandler();
		$output_view = new MockPswdRequestViewModel();
		$presenter = new MockPswdRequestPresenter();
		Controller::pswdRequest("user@domain.com", $data_access, $message_handler, $output_view, $presenter);
		$this->assertSame("SystemFailure", $output_view->err_msg);
	}

	public function testPostTokenFail()
	{
		$data_access = $this->createStub(MockDataAccess::class);
		$data_access->method('fetchUser')->will($this->returnValue(["email" => "user@domain.com"]));
		$data_access->method('deleteRequestToken')->will($this->returnValue(true));
		$data_access->method('postRequestToken')->will($this->returnValue(false));
		$message_handler = new MockMessageHandler();
		$output_view = new MockPswdRequestViewModel();
		$presenter = new MockPswdRequestPresenter();
		Controller::pswdRequest("user@domain.com", $data_access, $message_handler, $output_view, $presenter);
		$this->assertSame("SystemFailure", $output_view->err_msg);
		$this->clearDb();
	}
}
