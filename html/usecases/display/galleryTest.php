<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__.'/../../mocks/mockDataAccess/mockDataAccess.php';
require_once __DIR__.'/../../mocks/mockViewModels/mockGalleryViewModel.php';
require_once __DIR__.'/../../mocks/mockPresenter/mockGalleryPresenter.php';
require_once __DIR__.'/../../mocks/mockController/mockController.php';

final class galleryTest extends TestCase
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
	}


	public function clearDb() :void
	{
		$handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/users.csv", "w");
		fclose($handle);
		$handle = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/images.csv", "w");
		fclose($handle);
	}

	public function testSystemFailure()
	{
		$data_access = $this->createStub(MockDataAccess::class);
		$data_access->method('fetchImages')->will($this->returnValue(NULL));
		$output_view = new MockGalleryViewModel();
		$presenter = new MockGalleryPresenter();
		Controller::gallery('1000', $data_access, $output_view, $presenter);
		$this->assertSame("SystemFailure", $output_view->err_msg);
	}

	public function testUnauthorised()
	{
		$data_access = new MockDataAccess();
		$output_view = new MockGalleryViewModel();
		$presenter = new MockGalleryPresenter();
		Controller::privateGallery('', $data_access, $output_view, $presenter);
		$this->assertSame("Unauthorised", $output_view->err_msg);
	}

	public function testSuccess()
	{	
		$this->createDb();
		$data_access = new MockDataAccess();
		$output_view = new MockGalleryViewModel();
		$presenter = new MockGalleryPresenter();
		Controller::gallery('1000', $data_access, $output_view, $presenter);
		$this->assertSame("Success", $output_view->err_msg);
		$this->assertSame(4, count($output_view->gallery));
		Controller::privateGallery('1001', $data_access, $output_view, $presenter);
		$this->assertSame("Success", $output_view->err_msg);
		$this->assertSame(2, count($output_view->gallery));
		$this->clearDb();
	}
}
