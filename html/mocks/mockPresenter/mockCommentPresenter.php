<?php

require_once __DIR__.'/../../usecases/responses/comments.php';

class MockCommentPresenter implements CommentOutput
{
	public function commentOutput($status, $output_view)
	{
		switch ($status)
		{
			case Status::Success:
				$output_view->create("Success");
				break;	
			case Status::Unauthorised:
				$output_view->create("Unauthorised");
				break;
			case Status::CommentTooLong:
				$output_view->create("CommentTooLong");
				break;
			case Status::SystemFailure:
				$output_view->create("SystemFailure");
				break;
		}
	}
}
