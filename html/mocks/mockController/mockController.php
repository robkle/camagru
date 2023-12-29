<?php

require_once __DIR__.'/../../usecases/data/userInputData.php';
require_once __DIR__.'/../../usecases/data/confirmInputData.php';
require_once __DIR__.'/../../usecases/data/loginInputData.php';
require_once __DIR__.'/../../usecases/data/uploadInputData.php';
require_once __DIR__.'/../../usecases/data/pswdRequestInputData.php';
require_once __DIR__.'/../../usecases/data/pswdResetInputData.php';
require_once __DIR__.'/../../usecases/data/pswdRenewInputData.php';
require_once __DIR__.'/../../usecases/data/modifyUsernameInputData.php';
require_once __DIR__.'/../../usecases/data/modifyEmailInputData.php';
require_once __DIR__.'/../../usecases/data/changeNotificationsInputData.php';
require_once __DIR__.'/../../usecases/data/commentInputData.php';
require_once __DIR__.'/../../usecases/data/likeInputData.php';
require_once __DIR__.'/../../usecases/data/galleryInputData.php';
require_once __DIR__.'/../../usecases/data/imageInputData.php';
require_once __DIR__.'/../../usecases/users/signup.php';
require_once __DIR__.'/../../usecases/users/confirm.php';
require_once __DIR__.'/../../usecases/login/login.php';
require_once __DIR__.'/../../usecases/upload/upload.php';
require_once __DIR__.'/../../usecases/passwordRequest/request.php';
require_once __DIR__.'/../../usecases/passwordRequest/reset.php';
require_once __DIR__.'/../../usecases/passwordRequest/renew.php';
require_once __DIR__.'/../../usecases/users/modifyUsername.php';
require_once __DIR__.'/../../usecases/users/modifyEmail.php';
require_once __DIR__.'/../../usecases/users/changeNotifications.php';
require_once __DIR__.'/../../usecases/responses/comments.php';
require_once __DIR__.'/../../usecases/responses/likes.php';
require_once __DIR__.'/../../usecases/display/gallery.php';
require_once __DIR__.'/../../usecases/display/image.php';


class Controller
{
	static function signup($user, &$data_access, &$message_handler,  &$signup_view, &$presenter)
	{
		$inputData = new UserInputData($user, $data_access, $message_handler, $signup_view, $presenter);
		SignupInteractor::run($inputData);
	}

	static function confirm($confirm, &$data_access, &$confirm_view, &$presenter)
	{
		$confirmData = new ConfirmInputData($confirm, $data_access, $confirm_view, $presenter);
		ConfirmInteractor::run($confirmData);
	}

	static function login($credentials, &$data_access, &$login_view, &$presenter)
	{
		$loginData = new LoginInputData($credentials, $data_access, $login_view, $presenter);
		LoginInteractor::run($loginData);
	}

	static function upload($file, $dest, $filter, $userId, &$data_access, &$upload_view, &$presenter)
	{
		$uploadData = new UploadInputData($file, $dest, $filter, $userId, $data_access, $upload_view, $presenter);
		UploadInteractor::run($uploadData);
	}

	static function pswdRequest($email, &$data_access, &$message_handler, &$output_view, &$presenter)
	{
		$requestdata = new PswdRequestInputData($email, $data_access, $message_handler, $output_view, $presenter);
		PswdRequestInteractor::run($requestdata);
	}

	static function pswdReset($token, &$data_access, &$output_view, &$presenter)
	{
		$resetData = new PswdResetInputData($token, $data_access, $output_view, $presenter);
		PswdResetInteractor::run($resetData);
	}

	static function pswdRenew($user_id, $input, &$data_access, &$output_view,  &$presenter)
	{
		$renewData = new PswdRenewInputData($user_id, $input, $data_access, $output_view, $presenter);
		PswdRenewInteractor::run($renewData);
	}

	static function modifyUsername($user_id, $input, &$data_access, &$output_view,  &$presenter)
	{
		$modifyData = new ModifyUsernameInputData($user_id, $input, $data_access, $output_view, $presenter);
		ModifyUsernameInteractor::run($modifyData);
	}

	static function modifyEmail($user_id, $input, &$data_access, &$output_view,  &$presenter)
	{
		$modifyData = new ModifyEmailInputData($user_id, $input, $data_access, $output_view, $presenter);
		ModifyEmailInteractor::run($modifyData);
	}

	static function changeNotifications($user_id, $input, &$data_access, &$output_view,  &$presenter)
	{
		$changeData = new ChangeNotificationsInputData($user_id, $input, $data_access, $output_view, $presenter);
		ChangeNotificationsInteractor::run($changeData);
	}

	static function comment($user_id, $input, &$data_access, &$message_handler, &$output_view, &$presenter)
	{
		$commentData = new CommentInputData($user_id, $input, $data_access, $message_handler, $output_view, $presenter);
		CommentInteractor::run($commentData);
	}

	static function like($user_id, $input, &$data_access, &$message_handler, &$output_view, &$presenter)
	{
		$likeData = new LikeInputData($user_id, $input, $data_access, $message_handler, $output_view, $presenter);
		LikeInteractor::run($likeData);
	}

	static function gallery($user_id, &$data_access, &$output_view, &$presenter, $input = null)
	{
		$galleryData = new GalleryInputData($user_id, $input, $data_access, $output_view, $presenter);
		GalleryInteractor::run($galleryData);
	}

	static function privateGallery($user_id, &$data_access, &$output_view, &$presenter, $input = null)
	{
		$galleryData = new GalleryInputData($user_id, $input, $data_access, $output_view, $presenter);
		PrivateGalleryInteractor::run($galleryData);
	}

	static function image($input, &$data_access, &$output_view, &$presenter)
	{
		$inputData = new ImageInputData($input, $data_access, $output_view, $presenter);
		ImageInteractor::run($inputData);
	}
}
