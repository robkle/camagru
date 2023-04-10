<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__.'/../../mocks/mockDataAccess/mockDataAccess.php';
require_once __DIR__.'/../../mocks/mockMessageHandler/mockMessageHandler.php';
require_once __DIR__.'/../../mocks/mockViewModels/mockSignupViewModel.php';
require_once __DIR__.'/../../mocks/mockPresenter/mockSignupPresenter.php';
require_once __DIR__.'/../../mocks/mockController/mockController.php';

final class signupTest extends TestCase
{	
	public $userSignup;

	public function setUp() : void
	{
		$this->userSignup = array(
			"login" => "username",
			"email" => "user@domain.com",
			"pswd" => "#Qwerty12345!",
			"pswd2" => "#Qwerty12345!"
		);
	}


	public function clearDb() :void
	{
		$usersfile=fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv", "w");
		fclose($usersfile); 
	}

	public function clearEmail() :void
	{
		$email=fopen("/home/robkle/Projects/camagru/html/mocks/mockEmail/mockEmail.txt", "w");
		fclose($email); 
	}

	public function testSuccess()
	{	
		$data_access = new MockDataAccess();
		$message_handler = new MockMessageHandler();
		$signup_view = new MockSignupViewModel();
		$presenter = new MockSignupPresenter();
		Controller::signup($this->userSignup, $data_access, $message_handler, $signup_view, $presenter);
		$this->assertSame("Success", $signup_view->err_msg);
		$this->clearEmail();
	}

	public function testInvalidLogin()
	{
		$this->userSignup["login"] = "u";
		$data_access = new MockDataAccess();
		$message_handler = new MockMessageHandler();
		$signup_view = new MockSignupViewModel();
		$presenter = new MockSignupPresenter();
		Controller::signup($this->userSignup, $data_access, $message_handler, $signup_view, $presenter);
		$this->assertSame("InvalidLogin", $signup_view->err_msg);	
	}
	
	public function testExistingLogin()
	{
		$data_access = new MockDataAccess();
		$message_handler = new MockMessageHandler();
		$signup_view = new MockSignupViewModel();
		$presenter = new MockSignupPresenter();
		Controller::signup($this->userSignup, $data_access, $message_handler, $signup_view, $presenter);
		$this->assertSame("ExistingLogin", $signup_view->err_msg);	
	}

	public function testInvalidEmail()
	{
		$this->userSignup["login"] = "username2";
		$this->userSignup["email"] = "username2";
		$data_access = new MockDataAccess();
		$message_handler = new MockMessageHandler();
		$signup_view = new MockSignupViewModel();
		$presenter = new MockSignupPresenter();
		Controller::signup($this->userSignup, $data_access, $message_handler, $signup_view, $presenter);
		$this->assertSame("InvalidEmail", $signup_view->err_msg);	
	}

	public function testExistingEmail()
	{
		$this->userSignup["login"] = "username2";
		$data_access = new MockDataAccess();
		$message_handler = new MockMessageHandler();
		$signup_view = new MockSignupViewModel();
		$presenter = new MockSignupPresenter();
		Controller::signup($this->userSignup, $data_access, $message_handler, $signup_view, $presenter);
		$this->assertSame("ExistingEmail", $signup_view->err_msg);	
		$this->clearDb();
	}

	public function testConflictPassword()
	{
		$this->userSignup["pswd"] = "password";
		$data_access = new MockDataAccess();
		$message_handler = new MockMessageHandler();
		$signup_view = new MockSignupViewModel();
		$presenter = new MockSignupPresenter();
		Controller::signup($this->userSignup, $data_access, $message_handler, $signup_view, $presenter);
		$this->assertSame("ConflictPassword", $signup_view->err_msg);	
	}

	public function testInvalidPassword()
	{
		$this->userSignup["pswd"] = "password";
		$this->userSignup["pswd2"] = "password";
		$data_access = new MockDataAccess();
		$message_handler = new MockMessageHandler();
		$signup_view = new MockSignupViewModel();
		$presenter = new MockSignupPresenter();
		Controller::signup($this->userSignup, $data_access, $message_handler, $signup_view, $presenter);
		$this->assertSame("InvalidPassword", $signup_view->err_msg);	
	}
	
	public function testDbFetchFail()
	{
		$data_access = $this->createStub(MockDataAccess::class);
		$data_access->method('fetchUser')->will($this->returnValue([NULL]));
		$message_handler = new MockMessageHandler();
		$signup_view = new MockSignupViewModel();
		$presenter = new MockSignupPresenter();
		Controller::signup($this->userSignup, $data_access, $message_handler, $signup_view, $presenter);
		$this->assertSame("SystemFailure", $signup_view->err_msg);
	}

	public function testDbPostFail()
	{
		$data_access = $this->createStub(MockDataAccess::class);
		$data_access->method('fetchUser')->will($this->returnValue(["login" => null, "email" => null, "pswd" => null]));
		$data_access->method('postUser')->will($this->returnValue(false));
		$message_handler = new MockMessageHandler();
		$signup_view = new MockSignupViewModel();
		$presenter = new MockSignupPresenter();
		Controller::signup($this->userSignup, $data_access, $message_handler, $signup_view, $presenter);
		$this->assertSame("SystemFailure", $signup_view->err_msg);
	}

	public function testMessageFail()
	{
		$data_access = new MockDataAccess();
		$message_handler = $this->createStub(MockMessageHandler::class);
		$message_handler->method('signupEmail')->will($this->returnValue(False));
		$signup_view = new MockSignupViewModel();
		$presenter = new MockSignupPresenter();
		Controller::signup($this->userSignup, $data_access, $message_handler, $signup_view, $presenter);
		$this->assertSame("SystemFailure", $signup_view->err_msg);
		$this->clearDb();
	}
}
