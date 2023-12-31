<?php

require_once __DIR__.'/../../usecases/upload/upload.php';

class mockUploadPresenter implements UploadOutput
{
	public function uploadOutput($status, $upload_view)
	{
		switch ($status)
		{
			case Status::Success:
				$upload_view->create("Success");
				break;
			case Status::InvalidUser:
				$upload_view->create("InvalidUser");
				break;
			case Status::UploadError:
				$upload_view->create("UploadError");
				break;
			case Status::InvalidType:
				$upload_view->create("InvalidType");
				break;
			case Status::SizeLimit:
				$upload_view->create("SizeLimit");
				break;
			case Status::NoSource:
				$upload_view->create("NoSource");
				break;
			case Status::NoDestination:
				$upload_view->create("NoDestination");
				break;
			case Status::SystemFailure:
				$upload_view->create("SystemFailure");
				break;
		}
	}
}
