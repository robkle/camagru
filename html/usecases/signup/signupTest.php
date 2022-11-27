<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__.'/../../mocks/mockInput/signupInput.php';
require_once __DIR__.'/../../mocks/dataAccess/mockDataAccess.php';
require_once __DIR__.'/../../mocks/messageHandler/mockMessageHandler.php';
require_once __DIR__.'/../../mocks/viewModels/mockSignupViewModel.php';
require_once __DIR__.'/../../mocks/presenter/mockSignupPresenter.php';
require_once __DIR__.'/../../controller/controller.php';

final class signupTest extends TestCase
{	
	public $userSignup;

	public function setUp() :void
	{
		$this->userSignup = array(
			"login" => "username",
			"email" => "user@domain.com",
			"pswd" => "#Qwerty12345!",
			"pswd2" => "#Qwerty12345!"
		);
	}

	public function testSuccess()
	{	
		$data_access = new MockDataAccess();
		$message_handler = new MockMessageHandler();
		$signup_view = new MockSignupViewModel();
		$presenter = new MockSignupPresenter();
		Controller::signup($this->userSignup, $data_access, $message_handler, $signup_view, $presenter);
		$this->assertSame("Success", $signup_view->err_msg);
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
	}

	public function testInvalidPassword()
	{
		$this->assertSame(1,1);	
	}

	public function testConflictPassword()
	{
		$this->assertSame(1,1);	
	}

	public function testMessageFail()
	{
		$this->assertSame(1,1);	
	}
	
	public function testDbFetchFail()
	{
		$this->assertSame(1,1);	
	}

	public function testDbPostFail()
	{
		$this->assertSame(1,1);	
	}
}
