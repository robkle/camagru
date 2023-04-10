<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__.'/../../mocks/mockDataAccess/mockDataAccess.php';
require_once __DIR__.'/../../mocks/mockViewModels/mockUploadViewModel.php';
require_once __DIR__.'/../../mocks/mockPresenter/mockUploadPresenter.php';
require_once __DIR__.'/../../mocks/mockController/mockController.php';

final class uploadTest extends TestCase
{
	public function clear()
	{
		$handler = fopen("/home/robkle/Projects/camagru/html/mocks/mockDatabase/images.csv", "w");
		fclose($handler);
		//Delete all files in destination younger than 2 minutes
		$files = glob("/home/robkle/Projects/camagru/html/mocks/mockImages/destination/*");
		foreach($files as $file){
  			if(is_file($file) and (time() - filemtime($file)) < 120 ) {
    			unlink($file);
  			}
		}
	}
	
	public function testInvalidUser()
	{
		$data_access = $this->createStub(MockDataAccess::class);
		$data_access->method('fetchUser')->will($this->returnValue([NULL]));
		$upload_view = new MockUploadViewModel();
		$presenter = new MockUploadPresenter();
		Controller::upload([NULL], NULL, NULL, NULL, $data_access, $upload_view, $presenter);
		$this->assertSame("InvalidUser", $upload_view->err_msg);
		Controller::upload([NULL], NULL, NULL, 0, $data_access, $upload_view, $presenter);
		$this->assertSame("InvalidUser", $upload_view->err_msg);
	}

	public function testUploadError()
	{
		$data_access = $this->createStub(MockDataAccess::class);
		$data_access->method('fetchUser')->will($this->returnValue(["id" => 1000]));
		$file = ["error" => 1];
		$upload_view = new MockUploadViewModel();
		$presenter = new MockUploadPresenter();
		Controller::upload($file, NULL, NULL, 1000, $data_access, $upload_view, $presenter);
		$this->assertSame("UploadError", $upload_view->err_msg);
	}

	public function testNoSource()
	{
		$data_access = $this->createStub(MockDataAccess::class);
		$data_access->method('fetchUser')->will($this->returnValue(["id" => 1000]));
		$file = ["error" => 0];
		$upload_view = new MockUploadViewModel();
		$presenter = new MockUploadPresenter();
		Controller::upload($file, NULL, NULL, 1000, $data_access, $upload_view, $presenter);
		$this->assertSame("NoSource", $upload_view->err_msg);
		$file = ["error" => 0, "tmpName" => NULL];
		Controller::upload($file, NULL, NULL, 1000, $data_access, $upload_view, $presenter);
		$this->assertSame("NoSource", $upload_view->err_msg);
	}


	public function testInvalidType()
	{
		$data_access = $this->createStub(MockDataAccess::class);
		$data_access->method('fetchUser')->will($this->returnValue(["id" => 1000]));
		$file = [
			"tmp_name" => "/home/robkle/Projects/camagru/html/mocks/mockImages/source/connected.png",
			"error" => 0,
		];
		$upload_view = new MockUploadViewModel();
		$presenter = new MockUploadPresenter();
		Controller::upload($file, NULL, NULL, 1000, $data_access, $upload_view, $presenter);
		$this->assertSame("InvalidType", $upload_view->err_msg);
		$file = [
			"tmp_name" => "/home/robkle/Projects/camagru/html/mocks/mockImages/source/invalid.jpg",
			"error" => 0,
		];
		Controller::upload($file, NULL, NULL, 1000, $data_access, $upload_view, $presenter);
		$this->assertSame("InvalidType", $upload_view->err_msg);
	}

	public function testSizeLimit()
	{
		$data_access = $this->createStub(MockDataAccess::class);
		$data_access->method('fetchUser')->will($this->returnValue(["id" => 1000]));
		$file = [
			"tmp_name" => "/home/robkle/Projects/camagru/html/mocks/mockImages/source/landscape.jpg",
			"error" => 0,
		];
		$upload_view = new MockUploadViewModel();
		$presenter = new MockUploadPresenter();
		Controller::upload($file, NULL, NULL, 1000, $data_access, $upload_view, $presenter);
		$this->assertSame("SizeLimit", $upload_view->err_msg);
	}

	public function testNoDestination()
	{
		$file = [
			"tmp_name" => "/home/robkle/Projects/camagru/html/mocks/mockImages/source/dream.jpg",
			"error" => 0,
		];
		$filter = "0";
		$userId = 1000;
		$data_access = new MockDataAccess();
		$upload_view = new MockUploadViewModel();
		$presenter = new MockUploadPresenter();
		Controller::upload($file, NULL, $filter, $userId, $data_access, $upload_view, $presenter);
		$this->assertSame("NoDestination", $upload_view->err_msg);
	}

	public function testSuccess()
	{
		$file = [
			"name" => "dream.jpg", 
			"type" => "image/jpeg", 
			"tmp_name" => "/home/robkle/Projects/camagru/html/mocks/mockImages/source/dream.jpg",
			"error" => 0,
			"size" => 555671,
		];
		$dest = "/home/robkle/Projects/camagru/html/mocks/mockImages/destination/";
		$filter = "0";
		$userId = 1000;
		$data_access = new MockDataAccess();
		$upload_view = new MockUploadViewModel();
		$presenter = new MockUploadPresenter();
		Controller::upload($file, $dest, $filter, $userId, $data_access, $upload_view, $presenter);
		$this->assertSame("Success", $upload_view->err_msg);
		$this->clear();
	}

}
