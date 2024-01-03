<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__.'/../../mocks/mockDataAccess/mockDataAccess.php';
require_once __DIR__.'/../../mocks/mockViewModels/mockChangeNotificationsViewModel.php';
require_once __DIR__.'/../../mocks/mockPresenter/mockChangeNotificationsPresenter.php';
require_once __DIR__.'/../../mocks/mockController/mockController.php';

final class changeNotificationsTest extends TestCase
{	
	public function createUser($user_id, $notification)
	{
		$user = [$user_id, "user123", "email@domain.com", "#Pswd123!", "Yes", "12345", $notification];
		$handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv", "w");
		fputcsv($handle, $user);
		fclose($handle);
	}

	public function clearDb()
	{
		$usersfile=fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv", "w");
		fclose($usersfile); 
	}

	public function testUnauthorised()
	{
		$user_id = "1000";
		$this->createUser($user_id, "On");
		$data_access = new MockDataAccess();
		$output_view = new MockChangeNotificationsViewModel();
		$presenter = new MockChangeNotificationsPresenter();
		Controller::changeNotifications("", ["notifications" => "Off"], $data_access, $output_view, $presenter);
		$this->assertSame("Unauthorised", $output_view->err_msg);
		Controller::changeNotifications("2000", ["notifications" => "Off"], $data_access, $output_view, $presenter);
		$this->assertSame("Unauthorised", $output_view->err_msg);
	}

	public function testInvalidOption()
	{
		$data_access = new MockDataAccess();
		$output_view = new MockChangeNotificationsViewModel();
		$presenter = new MockChangeNotificationsPresenter();
		Controller::changeNotifications("1000", ["notifications" => "Asdf"], $data_access, $output_view, $presenter);
		$this->assertSame("InvalidOption", $output_view->err_msg);
	}

	public function testDbFetchFail()
	{
		$data_access = $this->createStub(MockDataAccess::class);
		$data_access->method('fetchUser')->will($this->returnValue([]));
		$output_view = new MockChangeNotificationsViewModel();
		$presenter = new MockChangeNotificationsPresenter();
		Controller::changeNotifications("1000", ["notifications" => "Off"], $data_access, $output_view, $presenter);
		$this->assertSame("SystemFailure", $output_view->err_msg);
	}

	public function testDbModifyFail()
	{
		$data_access = $this->createStub(MockDataAccess::class);
		$data_access->method('fetchUser')->will($this->returnValue(["id" => "1000"]));
		$data_access->method('changeNotifications')->will($this->returnValue(FALSE));
		$output_view = new MockChangeNotificationsViewModel();
		$presenter = new MockChangeNotificationsPresenter();
		Controller::changeNotifications("1000", ["notifications" => "Off"], $data_access, $output_view, $presenter);
		$this->assertSame("SystemFailure", $output_view->err_msg);
	}

	public function testSuccess()
	{
		$this->createUser("1000", "On");
		$data_access = new MockDataAccess();
		$output_view = new MockChangeNotificationsViewModel();
		$presenter = new MockChangeNotificationsPresenter();
		Controller::changeNotifications("1000", ["notifications" => "Off"], $data_access, $output_view, $presenter);
		$this->assertSame("Success", $output_view->err_msg);
		Controller::changeNotifications("1000", ["notifications" => "Comments"], $data_access, $output_view, $presenter);
		$this->assertSame("Success", $output_view->err_msg);
		Controller::changeNotifications("1000", ["notifications" => "Likes"], $data_access, $output_view, $presenter);
		$this->assertSame("Success", $output_view->err_msg);
		Controller::changeNotifications("1000", ["notifications" => "On"], $data_access, $output_view, $presenter);
		$this->assertSame("Success", $output_view->err_msg);
		$this->clearDb();
	}
}
