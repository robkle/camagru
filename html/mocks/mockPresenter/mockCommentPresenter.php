<?php

require_once __DIR__.'/../../usecases/interfaces/commentOutputInterface.php';

class MockCommentPresenter implements CommentOutput
{
	public function commentOutput($status, $output_view)
	{
		switch ($status)
		{
			case CommentStatus::Success:
				$output_view->create("Success");
				break;	
			case CommentStatus::Unauthorised:
				$output_view->create("Unauthorised");
				break;
			case CommentStatus::CommentTooLong:
				$output_view->create("CommentTooLong");
				break;
			case CommentStatus::SystemFailure:
				$output_view->create("SystemFailure");
				break;
		}
	}
}
