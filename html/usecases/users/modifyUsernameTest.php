<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__.'/../../mocks/mockDataAccess/mockDataAccess.php';
require_once __DIR__.'/../../mocks/mockViewModels/mockModifyUsernameViewModel.php';
require_once __DIR__.'/../../mocks/mockPresenter/mockModifyUsernamePresenter.php';
require_once __DIR__.'/../../mocks/mockController/mockController.php';

final class modifyUsernameTest extends TestCase
{	
	public function createUser($user_id, $login)
	{
		$pswd_encrypt = password_hash("#Pswd123!", PASSWORD_DEFAULT, ['cost'=>12]);
		$user = [$user_id, $login, "email@domain.com", $pswd_encrypt, "Yes", "12345", "On"];
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
		$this->createUser($user_id, "user123");
		$data_access = new MockDataAccess();
		$output_view = new MockModifyUsernameViewModel();
		$presenter = new MockModifyUsernamePresenter();
		Controller::modifyUsername("", ["username" => "newUsername"], $data_access, $output_view, $presenter);
		$this->assertSame("Unauthorised", $output_view->err_msg);
		Controller::modifyUsername("2000", ["username" => "newUsername"], $data_access, $output_view, $presenter);
		$this->assertSame("Unauthorised", $output_view->err_msg);
	}

	public function testInvalidUsername()
	{
		$data_access = new MockDataAccess();
		$output_view = new MockModifyUsernameViewModel();
		$presenter = new MockModifyUsernamePresenter();
		Controller::modifyUsername("1000", ["username" => "Nemo"], $data_access, $output_view, $presenter);
		$this->assertSame("InvalidUsername", $output_view->err_msg);
	}

	public function testInvalidPassword()
	{
		$user_old = "oldUsername";
		$this->createUser("1000", $user_old);
		$user_new = "newUsername";
		$data_access = new MockDataAccess();
		$output_view = new MockModifyUsernameViewModel();
		$presenter = new MockModifyUsernamePresenter();
		Controller::modifyUsername("1000", ["username" => $user_new, "pswd" => "Pass123"], $data_access, $output_view, $presenter);
		$this->assertSame("InvalidPassword", $output_view->err_msg);
		$this->clearDb();

	}

	public function testDbFetchFail()
	{
		$data_access = $this->createStub(MockDataAccess::class);
		$data_access->method('fetchUser')->will($this->returnValue([]));
		$output_view = new MockModifyUsernameViewModel();
		$presenter = new MockModifyUsernamePresenter();
		Controller::modifyUsername("1000", ["username" => "newUsername"], $data_access, $output_view, $presenter);
		$this->assertSame("SystemFailure", $output_view->err_msg);
		
	}

	public function testDbModifyFail()
	{
		$data_access = $this->createStub(MockDataAccess::class);
		$data_access->method('changeUsername')->will($this->returnValue(FALSE));
		$output_view = new MockModifyUsernameViewModel();
		$presenter = new MockModifyUsernamePresenter();
		Controller::modifyUsername("1000", ["username" => "newUsername", "pswd" => "#Pswd123!"], $data_access, $output_view, $presenter);
		$this->assertSame("SystemFailure", $output_view->err_msg);
	}

	public function testSuccess()
	{
		$user_old = "oldUsername";
		$this->createUser("1000", $user_old);
		$user_new = "newUsername";
		$data_access = new MockDataAccess();
		$output_view = new MockModifyUsernameViewModel();
		$presenter = new MockModifyUsernamePresenter();
		Controller::modifyUsername("1000", ["username" => $user_new, "pswd" => "#Pswd123!"], $data_access, $output_view, $presenter);
		$this->assertSame("Success", $output_view->err_msg);
		$this->clearDb();
	}
}
