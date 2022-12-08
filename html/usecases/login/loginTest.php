<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__.'/../../mocks/dataAccess/mockDataAccess.php';
require_once __DIR__.'/../../mocks/viewModels/mockLoginViewModel.php';
require_once __DIR__.'/../../mocks/presenter/mockLoginPresenter.php';
require_once __DIR__.'/../../controller/controller.php';

final class loginTest extends TestCase
{
	public function createUser($login, $email, $pswd, $confirmed)
	{
		$pswd_encrypt = password_hash($pswd, PASSWORD_DEFAULT, ['cost'=>12]);
		$user = [1000, $login, $email, $pswd_encrypt, $confirmed, "12345"];
		$handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv", "w");
		fputcsv($handle, $user);
		fclose($handle);
	}

	public function clearUser($login, $email, $pswd, $confimed)
	{
		$handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv", "w");
		fclose($handle);
	}

	public function testSuccess()
	{
		$this->createUser("username", "user@domain.com", "#Qwerty12345!", "Yes");
		$credentials = ["login" => "username", "pswd" => "#Qwerty12345!"];
		$data_access = new MockDataAccess();
		$login_view = new MockLoginViewModel();
		$presenter = new MockLoginPresenter();
		Controller::login($credentials, $data_access, $login_view, $presenter);
		$this->assertSame("Success", $login_view->err_msg);
	}
	
	public function testInvalidLogin()
	{
		$credentials = ["login" => "user", "pswd" => "#Qwerty12345!"];
		$data_access = new MockDataAccess();
		$login_view = new MockLoginViewModel();
		$presenter = new MockLoginPresenter();
		Controller::login($credentials, $data_access, $login_view, $presenter);
		$this->assertSame("InvalidLogin", $login_view->err_msg);
	}

	public function testInvalidPassword()
	{
		$credentials = ["login" => "username", "pswd" => "password"];
		$data_access = new MockDataAccess();
		$login_view = new MockLoginViewModel();
		$presenter = new MockLoginPresenter();
		Controller::login($credentials, $data_access, $login_view, $presenter);
		$this->assertSame("InvalidPassword", $login_view->err_msg);
	}

	public function testAccountUnconfirmed()
	{
		$this->createUser("username", "user@domain.com", "#Qwerty12345!", "No");
		$credentials = ["login" => "username", "pswd" => "#Qwerty12345!"];
		$data_access = new MockDataAccess();
		$login_view = new MockLoginViewModel();
		$presenter = new MockLoginPresenter();
		Controller::login($credentials, $data_access, $login_view, $presenter);
		$this->assertSame("AccountUnconfirmed", $login_view->err_msg);
	}

	public function testSystemFailure()
	{
		$credentials = ["login" => "username", "pswd" => "#Qwerty12345!"];
		$data_access = $this->createStub(MockDataAccess::class);
		$data_access->method('fetchUser')->will($this->returnValue([NULL]));
		$login_view = new MockLoginViewModel();
		$presenter = new MockLoginPresenter();
		Controller::login($credentials, $data_access, $login_view, $presenter);
		$this->assertSame("SystemFailure", $login_view->err_msg);
	}
}
