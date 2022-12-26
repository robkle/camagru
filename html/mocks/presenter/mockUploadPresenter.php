<?php

require_once __DIR__.'/../../usecases/interfaces/uploadOutputInterface.php';

class mockUploadPresenter implements UploadOutput
{
	public function uploadOutput($status, $upload_view)
	{
		switch ($status)
		{
			case UploadStatus::Success:
				$upload_view->create("Success");
				break;
			case UploadStatus::InvalidUser:
				$upload_view->create("InvalidUser");
				break;
			case UploadStatus::UploadError:
				$upload_view->create("UploadError");
				break;
			case UploadStatus::InvalidType:
				$upload_view->create("InvalidType");
				break;
			case UploadStatus::SizeLimit:
				$upload_view->create("SizeLimit");
				break;
			case UploadStatus::NoSource:
				$upload_view->create("NoSource");
				break;
			case UploadStatus::SystemFailure:
				$upload_view->create("SystemFailure");
				break;
		}
	}
}
