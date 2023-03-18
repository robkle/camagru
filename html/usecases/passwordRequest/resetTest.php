<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__.'/../../mocks/mockDataAccess/mockDataAccess.php';
require_once __DIR__.'/../../mocks/mockViewModels/mockPswdResetViewModel.php';
require_once __DIR__.'/../../mocks/mockPresenter/mockPswdResetPresenter.php';
require_once __DIR__.'/../../mocks/mockController/mockController.php';

final class resetTest extends TestCase
{
	function createRequest($email, $token, $timeout)
	{
		$line = [1000, $email, $token, $timeout];
		$handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/pswdRequest.csv", "w");
		fputcsv($handle, $line);
		fclose($handle);
		if ($token !== null) {
			$body = "http://127.0.0.1:8080/mockRequest.php?token=$token";
		} else {
			$body = "http://127.0.0.1:8080/mockRequest.php";
		}
		$handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockEmail/mockRequest.txt", "w");
		fwrite($handle, $body);
		fclose($handle);
	}

	public function createUser($login, $email, $pswd, $confirmed)
	{
		$pswd_encrypt = password_hash($pswd, PASSWORD_DEFAULT, ['cost'=>12]);
		$user = [1000, $login, $email, $pswd_encrypt, $confirmed, "12345"];
		$handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv", "w");
		fputcsv($handle, $user);
		fclose($handle);
	}

	public function clear() :void
	{
		$usersfile=fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv", "w");
		fclose($usersfile); 
		$usersfile=fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/pswdRequest.csv", "w");
		fclose($usersfile); 
		$usersfile = fopen("/home/robkle/Projects/camagru/html/mocks/mockEmail/mockRequest.txt", "w");
		fclose($usersfile); 
	}

	function getEmail() {
		$handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockEmail/mockRequest.txt", "r");
		$line = fgets($handle);
		$url_components = parse_url($line);
		parse_str($url_components['query'], $token);
		return $token;
	}

	public function testQueryInvalid()
	{
		$data_access = new MockDataAccess();
		$output_view = new MockPswdResetViewModel();
		$presenter = new MockPswdResetPresenter();
		Controller::pswdReset(null, $data_access, $output_view, $presenter);
		$this->assertSame("QueryInvalid", $output_view->err_msg);
		Controller::pswdReset(["key"=>"abc"], $data_access, $output_view, $presenter);
		$this->assertSame("QueryInvalid", $output_view->err_msg);
		Controller::pswdReset([null], $data_access, $output_view, $presenter);
		$this->assertSame("QueryInvalid", $output_view->err_msg);
	}

	public function testTimeOut()
	{	
		$token = bin2hex(random_bytes(32));
		$timeout = date("U") - 60;
		$this->createRequest("user@domain.com", $token, $timeout);
		$input = $this->getEmail();
		$data_access = new MockDataAccess();
		$output_view = new MockPswdResetViewModel();
		$presenter = new MockPswdResetPresenter();
		Controller::pswdReset($input, $data_access, $output_view, $presenter);
		$this->assertSame("TimeOut", $output_view->err_msg);
	}

	public function testInvalidEmail()
	{
		$this->createUser("username", "user@domain.com", "#Qwerty12345!", "Yes");
		$token = "token";
		$timeout = date("U") + 120;
		$this->createRequest("wrong@email.com", $token, $timeout);
		$input = $this->getEmail();
		$data_access = new MockDataAccess();
		$output_view = new MockPswdResetViewModel();
		$presenter = new MockPswdResetPresenter();
		Controller::pswdReset($input, $data_access, $output_view, $presenter);
		$this->assertSame("InvalidEmail", $output_view->err_msg);
	}
	
	public function testSuccess()
	{	
		$this->createUser("username", "user@domain.com", "#Qwerty12345!", "Yes");
		$token = "token";
		$timeout = date("U") + 120;
		$this->createRequest("user@domain.com", $token, $timeout);
		$input = $this->getEmail();
		$data_access = new MockDataAccess();
		$output_view = new MockPswdResetViewModel();
		$presenter = new MockPswdResetPresenter();
		Controller::pswdReset($input, $data_access, $output_view, $presenter);
		$this->assertSame("Success", $output_view->err_msg);
		$this->clear();
	}

}
