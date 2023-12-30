<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__.'/../../mocks/mockDataAccess/mockDataAccess.php';
require_once __DIR__.'/../../mocks/mockViewModels/mockRemoveViewModel.php';
require_once __DIR__.'/../../mocks/mockPresenter/mockRemovePresenter.php';
require_once __DIR__.'/../../mocks/mockController/mockController.php';

final class removeTest extends TestCase
{	

	public function createDb() : void
	{
		$users = array(
			array("1000", "user123", "user123@domain.com", "#Pswd123!", "Yes", "12345", "On"),
			array("1001", "user456", "user456@domain.com", "#Pswd456!", "Yes", "12345", "On"),
			array("1002", "user789", "user789@domain.com", "#Pswe789!", "Yes", "12345", "On"),
			
		);
		$handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv", "a");
		foreach($users as $user) {
			fputcsv($handle, $user);
		}
		fclose($handle);
		$images = array(
			array("0001", "1001", "15052023001.jpg", "6"),
			array("0002", "1002", "23062023001.jpg", "3"),
			array("0003", "1002", "17092023001.jpg", "0"),
			array("0004", "1001", "04112023001.jpg", "1"),
		);
		$handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/images.csv", "w");
		foreach($images as $image) {
			fputcsv($handle, $image);
		}
		fclose($handle);
		$comments = array(
			array("0100", "0001", "1002", "Ha ha, nice one!" ),
			array("0101", "0001", "1000", "Lol, crazy!"),
			array("0102", "0003", "1001", "Cool!!"),
		);
		$handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/comments.csv", "w");
		foreach($comments as $comment) {
			fputcsv($handle, $comment);
		}
		fclose($handle);
		//$like = [$like_id, $image_id, $user_id];
		$likes = array(
			array("0010", "0001", "1002"),
			array("0011", "0001", "1000"),
			array("0012", "0003", "1001"),
		);
		$handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/likes.csv", "w");
		foreach($likes as $like) {
			fputcsv($handle, $like);
		}
		fclose($handle);
	}


	public function clearDb() :void
	{
		$handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv", "w");
		fclose($handle);
		$handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/images.csv", "w");
		fclose($handle);
		$handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/comments.csv", "w");
		fclose($handle);
	}

	public function testUnauthorized()
	{
		$data_access = new MockDataAccess();
		$output_view = new MockRemoveViewModel();
		$presenter = new MockRemovePresenter();
		Controller::remove('', ["image_id" => "0001"], $data_access, $output_view, $presenter);
		$this->assertSame("Unauthorized", $output_view->err_msg);
		Controller::remove('9999', ["image_id" => "0001"], $data_access, $output_view, $presenter);
		$this->assertSame("Unauthorized", $output_view->err_msg);
	}

	public function testSystemFailure()
	{
		$user_id = '1001';
		$data_access = $this->createStub(MockDataAccess::class);
		$data_access->method('fetchUser')->will($this->returnValue(["id" => $user_id]));
		$data_access->method('removeImage')->will($this->returnValue(FALSE));
		$output_view = new MockRemoveViewModel();
		$presenter = new MockRemovePresenter();
		Controller::remove($user_id, ["image_id" => "0001"], $data_access, $output_view, $presenter);
		$this->assertSame("SystemFailure", $output_view->err_msg);
	}

	public function testSuccess()
	{	
		$this->createDb();
		$data_access = new MockDataAccess();
		$output_view = new MockRemoveViewModel();
		$presenter = new MockRemovePresenter();
		Controller::remove('1001', ["image_id" => "0001"], $data_access, $output_view, $presenter);
		$this->assertSame("Success", $output_view->err_msg);
		$this->clearDb();
	}
}
