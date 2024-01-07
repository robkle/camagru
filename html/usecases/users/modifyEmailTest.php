<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__.'/../../mocks/mockDataAccess/mockDataAccess.php';
require_once __DIR__.'/../../mocks/mockViewModels/mockModifyEmailViewModel.php';
require_once __DIR__.'/../../mocks/mockPresenter/mockModifyEmailPresenter.php';
require_once __DIR__.'/../../mocks/mockController/mockController.php';

final class modifyEmailTest extends TestCase
{
	public function createUser($user_id, $email)
	{
		$pswd_encrypt = password_hash("#Pswd123!", PASSWORD_DEFAULT, ['cost'=>12]);
		$user = [$user_id, "user123", $email, $pswd_encrypt, "Yes", "12345", "On"];
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
		$this->createUser($user_id, "email@domain.com");
		$data_access = new MockDataAccess();
		$output_view = new MockModifyEmailViewModel();
		$presenter = new MockModifyEmailPresenter();
		Controller::modifyEmail("", ["email" => "newEmail@domain.com", "pswd" => ""], $data_access, $output_view, $presenter);
		$this->assertSame("Unauthorised", $output_view->err_msg);
		Controller::modifyEmail("2000", ["email" => "newEmail@domain.com"], $data_access, $output_view, $presenter);
		$this->assertSame("Unauthorised", $output_view->err_msg);
	}

	public function testInvalidEmail()
	{
		$data_access = new MockDataAccess();
		$output_view = new MockModifyEmailViewModel();
		$presenter = new MockModifyEmailPresenter();
		Controller::modifyEmail("1000", ["email" => "newEmail", "pswd" => ""], $data_access, $output_view, $presenter);
		$this->assertSame("InvalidEmail", $output_view->err_msg);
	}

	public function testInvalidPassword()
	{
		$this->createUser("1000", "email@domain.com");
		$data_access = new MockDataAccess();
		$output_view = new MockModifyEmailViewModel();
		$presenter = new MockModifyEmailPresenter();
		Controller::modifyEmail("1000", ["email" => "newEmail@domain.com", "pswd" => "Pass123"], $data_access, $output_view, $presenter);
		$this->assertSame("InvalidPassword", $output_view->err_msg);
		$this->clearDb();

	}

	public function testDbFetchFail()
	{
		$data_access = $this->createStub(MockDataAccess::class);
		$data_access->method('fetchUser')->will($this->returnValue([]));
		$output_view = new MockModifyEmailViewModel();
		$presenter = new MockModifyEmailPresenter();
		Controller::modifyEmail("1000", ["email" => "newEmail@domain.com", "pswd" => "#Pswd123!"], $data_access, $output_view, $presenter);
		$this->assertSame("SystemFailure", $output_view->err_msg);
	}

	public function testDbModifyFail()
	{
		$data_access = $this->createStub(MockDataAccess::class);
		$data_access->method('changeEmail')->will($this->returnValue(FALSE));
		$output_view = new MockModifyEmailViewModel();
		$presenter = new MockModifyEmailPresenter();
		Controller::modifyEmail("1000", ["email" => "newEmail@domain.com", "pswd" => "#Pswd123!"], $data_access, $output_view, $presenter);
		$this->assertSame("SystemFailure", $output_view->err_msg);
	}

	public function testSuccess()
	{
		$this->createUser("1000", "email@domain.com");
		$data_access = new MockDataAccess();
		$output_view = new MockModifyEmailViewModel();
		$presenter = new MockModifyEmailPresenter();
		Controller::modifyEmail("1000", ["email" => "newEmail@domain.com", "pswd" => "#Pswd123!"], $data_access, $output_view, $presenter);
		$this->assertSame("Success", $output_view->err_msg);
		$this->clearDb();
	}
}
