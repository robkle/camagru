<?php

require_once __DIR__.'/../../usecases/interfaces/likeOutputInterface.php';

class MockLikePresenter implements LikeOutput
{
	public function likeOutput($status, $output_view)
	{
		switch ($status)
		{
			case LikeStatus::Success:
				$output_view->create("Success");
				break;	
			case LikeStatus::Unauthorised:
				$output_view->create("Unauthorised");
				break;
			case LikeStatus::SystemFailure:
				$output_view->create("SystemFailure");
				break;
		}
	}
}
