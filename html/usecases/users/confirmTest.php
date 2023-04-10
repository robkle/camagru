<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__.'/../../mocks/mockDataAccess/mockDataAccess.php';
require_once __DIR__.'/../../mocks/mockMessageHandler/mockMessageHandler.php';
require_once __DIR__.'/../../mocks/mockViewModels/mockConfirmViewModel.php';
require_once __DIR__.'/../../mocks/mockPresenter/mockConfirmPresenter.php';
require_once __DIR__.'/../../mocks/mockController/mockController.php';

final class confirmTest extends TestCase
{	
	public function createSignup($login, $email, $pswd, $ckey)
	{
		$user = ["1000", $login, $email, $pswd, "No", $ckey, "On"];
		$handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv", "w");
		fputcsv($handle, $user);
		fclose($handle);
		if ($ckey !== null) {
			$body = "http://127.0.0.1:8080/mockConfirm.php?ckey=$ckey";
		} else {
			$body = "http://127.0.0.1:8080/mockConfirm.php";
		}	
		$handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockEmail/mockEmail.txt", "w");
		fwrite($handle, $body);
		fclose($handle);
	}

	function getEmail() {
		$handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockEmail/mockEmail.txt", "r");
		$line = fgets($handle);
		$confirm = ["ckey" => substr($line, 43)];
		fclose($handle);
		return $confirm;
	}

	public function clearSignup() :void
	{
		$usersfile=fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv", "w");
		fclose($usersfile); 
		$email=fopen("/home/robkle/Projects/camagru/html/mocks/mockEmail/mockEmail.txt", "w");
		fclose($email); 
	}

	public function testSuccess()
	{
		$this->createSignup("username", "user@domain.com", "#Qwerty12345!", "abc123def456");	
		$confirm = $this->getEmail();
		$data_access = new MockDataAccess();
		$confirm_view = new MockConfirmViewModel();
		$presenter = new MockConfirmPresenter();
		Controller::confirm($confirm, $data_access, $confirm_view, $presenter);
		$this->assertSame("Success", $confirm_view->err_msg);
	}

	public function testSystemFailure()
	{	
		$confirm = $this->getEmail();
		$data_access = $this->createStub(MockDataAccess::class);
		$data_access->method('fetchCkey')->will($this->returnValue([NULL]));
		$confirm_view = new MockConfirmViewModel();
		$presenter = new MockConfirmPresenter();
		Controller::confirm($confirm, $data_access, $confirm_view, $presenter);
		$this->assertSame("SystemFailure", $confirm_view->err_msg);
	}

	public function testAccountConfirmed()
	{	
		$confirm = $this->getEmail();
		$data_access = new MockDataAccess();
		$confirm_view = new MockConfirmViewModel();
		$presenter = new MockConfirmPresenter();
		Controller::confirm($confirm, $data_access, $confirm_view, $presenter);
		$this->assertSame("AccountConfirmed", $confirm_view->err_msg);
	}
	
	public function testAccountInvalid()
	{	
		$confirm = $this->getEmail();
		$data_access = $this->createStub(MockDataAccess::class);
		$data_access->method('fetchCkey')->will($this->returnValue(["confirm" => "No", "ckey" => null]));
		$confirm_view = new MockConfirmViewModel();
		$presenter = new MockConfirmPresenter();
		Controller::confirm($confirm, $data_access, $confirm_view, $presenter);
		$this->assertSame("AccountInvalid", $confirm_view->err_msg);
	}
	
	public function testQueryInvalid()
	{	
		$this->createSignup("username", "user@domain.com", "#Qwerty12345!", null);	
		$confirm = $this->getEmail();
		$data_access = new MockDataAccess();
		$confirm_view = new MockConfirmViewModel();
		$presenter = new MockConfirmPresenter();
		Controller::confirm($confirm, $data_access, $confirm_view, $presenter);
		$this->assertSame("QueryInvalid", $confirm_view->err_msg);
		$this->clearSignup();
	}
}
