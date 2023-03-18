<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__.'/../../mocks/mockDataAccess/mockDataAccess.php';
require_once __DIR__.'/../../mocks/mockViewModels/mockPswdRenewViewModel.php';
require_once __DIR__.'/../../mocks/mockPresenter/mockPswdRenewPresenter.php';
require_once __DIR__.'/../../mocks/mockController/mockController.php';

final class renewTest extends TestCase
{
	public function createUser()
	{
		$user = [1000, "user", "user@domain.com", "Password", "Yes", "12345"];
		$handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv", "w");
		fputcsv($handle, $user);
		fclose($handle);
	}
	public function testConflictPassword()
	{
		$data_access = new MockDataAccess();
		$output_view = new MockPswdRenewViewModel();
		$presenter = new MockPswdRenewPresenter();
		Controller::pswdRenew("1000", ["pswd" => "#Password123", "pswd2" => "Password"], $data_access, $output_view, $presenter);
		$this->assertSame("ConflictPassword", $output_view->err_msg);
	}
	public function testInvalidPassword()
	{
		$data_access = new MockDataAccess();
		$output_view = new MockPswdRenewViewModel();
		$presenter = new MockPswdRenewPresenter();
Controller::pswdRenew("1000", ["pswd" => "Password", "pswd2" => "Password"], $data_access, $output_view, $presenter);
		$this->assertSame("InvalidPassword", $output_view->err_msg);
	}
	public function testSystemFailure()
	{
		$data_access = $this->createStub(MockDataAccess::class);
		$data_access->method('changePassword')->will($this->returnValue(false));
		$output_view = new MockPswdRenewViewModel();
		$presenter = new MockPswdRenewPresenter();
		Controller::pswdRenew("1000", ["pswd" => "#Password123", "pswd2" => "#Password123"], $data_access, $output_view, $presenter);
		$this->assertSame("SystemFailure", $output_view->err_msg);
	}
	public function testSuccess()
	{
		$this->createUser();
		$data_access = new MockDataAccess();
		$output_view = new MockPswdRenewViewModel();
		$presenter = new MockPswdRenewPresenter();
		Controller::pswdRenew("1000", ["pswd" => "#Password123", "pswd2" => "#Password123"], $data_access, $output_view, $presenter);
		$this->assertSame("Success", $output_view->err_msg);
	}
}
