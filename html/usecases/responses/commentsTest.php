<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__.'/../../mocks/mockDataAccess/mockDataAccess.php';
require_once __DIR__.'/../../mocks/mockMessageHandler/mockMessageHandler.php';
require_once __DIR__.'/../../mocks/mockViewModels/mockCommentViewModel.php';
require_once __DIR__.'/../../mocks/mockPresenter/mockCommentPresenter.php';
require_once __DIR__.'/../../mocks/mockController/mockController.php';

final class commentsTest extends TestCase
{	

	public function createDb() : void
	{
		$users = array(
			array("1000", "user123", "user123@domain.com", "#Pswd123!", "Yes", "12345", "On"),
			array("1001", "user456", "user456@domain.com", "#Pswd456!", "Yes", "12345", "On"),
			
		);
		$handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv", "a");
		foreach($users as $user) {
			fputcsv($handle, $user);
		}
		fclose($handle);
		$image = array(
			"0001", "1001", "15052023001.jpg");
		$handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/images.csv", "w");
		fputcsv($handle, $image);
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

	public function clearEmail() :void
	{
		$handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockEmail/mockComments.txt", "w");
		fclose($handle);
	}

	public function testCommentTooLong()
	{
		$data_access = new MockDataAccess();
		$message_handler = new MockMessageHandler();
		$output_view = new MockCommentViewModel();
		$presenter = new MockCommentPresenter();
		$comment = "";
		for ($i = 0; $i < 10; $i++) {
			$comment .= "This comment is way too long. I repeat: ";
		}
		Controller::comment(NULL, ['image_id' => null, 'comment' => $comment], $data_access, $message_handler, $output_view, $presenter);
		$this->assertSame("CommentTooLong", $output_view->err_msg);
	}

	public function testUnauthorised()
	{
		$data_access = new MockDataAccess();
		$message_handler = new MockMessageHandler();
		$output_view = new MockCommentViewModel();
		$presenter = new MockCommentPresenter();
		Controller::comment(NULL, NULL, $data_access, $message_handler, $output_view, $presenter);
		$this->assertSame("Unauthorised", $output_view->err_msg);
		$this->createDb();
		Controller::comment('2000', NULL, $data_access, $message_handler, $output_view, $presenter);
		$this->assertSame("Unauthorised", $output_view->err_msg);
	}


	public function testSystemFailure()
	{
		$output_view = new MockCommentViewModel();
		$message_handler = new MockMessageHandler();
		$output_view = new MockCommentViewModel();
		$presenter = new MockCommentPresenter();

		//TEST fetch user failure 
		$data_access = $this->createStub(MockDataAccess::class);
		$data_access->method('fetchUser')->will($this->returnValue([NULL]));
		Controller::comment('1000', ['image_id' => 1000, 'comment' => 'Cool image!'], $data_access, $message_handler, $output_view, $presenter);
		$this->assertSame("SystemFailure", $output_view->err_msg);
		
		// TEST fetchImageInfo failure
		$data_access = $this->createStub(MockDataAccess::class);
		$data_access->method('fetchUser')->will($this->returnValue(["id" => "1000"]));
		$data_access->method('fetchImageInfo')->will($this->returnValue([NULL]));
		Controller::comment('1000', ['image_id' => 1000, 'comment' => 'Cool image!'], $data_access, $message_handler, $output_view, $presenter);
		$this->assertSame("SystemFailure", $output_view->err_msg);
		$data_access = new MockDataAccess();
		Controller::comment('1000', ['image_id' => 1000, 'comment' => 'Cool image!'], $data_access, $message_handler, $output_view, $presenter);
		$this->assertSame("SystemFailure", $output_view->err_msg);
		


		//TEST post comment failure
		
		$image = ["id" => "001", "user_id" => "1001", "image" => "image.jpg"];
		$data_access = $this->createStub(MockDataAccess::class);
		$data_access->method('fetchUser')->will($this->returnValue(["id" => "1000"]));
		$data_access->method('fetchImageInfo')->will($this->returnValue($image));
		$data_access->method('postComment')->will($this->returnValue(false));
		Controller::comment('1000', ['image_id' => 1000, 'comment' => 'Cool image!'], $data_access, $message_handler, $output_view, $presenter);
		$this->assertSame("SystemFailure", $output_view->err_msg);

		//TEST email notification failure

		$data_access = new MockDataAccess();
		$message_handler = $this->createStub(MockMessageHandler::class);
		$message_handler->method('commentNotification')->will($this->returnValue(false));
		Controller::comment('1000', ['image_id' => 0001, 'comment' => 'Cool image!'], $data_access, $message_handler, $output_view, $presenter);
		$this->assertSame("SystemFailure", $output_view->err_msg);

	}

	public function testSuccess()
	{	
		$data_access = new MockDataAccess();
		$message_handler = new MockMessageHandler();
		$output_view = new MockCommentViewModel();
		$presenter = new MockCommentPresenter();
		Controller::comment('1000', ['image_id' => 0001, 'comment' => 'Cool image!'], $data_access, $message_handler, $output_view, $presenter);
		$this->assertSame("Success", $output_view->err_msg);
		$this->clearDb();
		$this->clearEmail();
	}
}
