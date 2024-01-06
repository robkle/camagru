<?php

require_once __DIR__.'/../mocks/mockDataAccess/mockDataAccess.php'; //TEMP
require_once __DIR__.'/../mocks/mockMessageHandler/mockMessageHandler.php'; //TEMP
require_once __DIR__.'/../presenters/signupPresenter.php';
require_once __DIR__.'/../presenters/confirmPresenter.php';
require_once __DIR__.'/../presenters/loginPresenter.php';
require_once __DIR__.'/../presenters/modifyEmailPresenter.php';
require_once __DIR__.'/../presenters/modifyUsernamePresenter.php';

require_once __DIR__.'/../viewModels/signupViewModel.php';
require_once __DIR__.'/../viewModels/confirmViewModel.php';
require_once __DIR__.'/../viewModels/loginViewModel.php';
require_once __DIR__.'/../viewModels/modifyEmailViewModel.php';
require_once __DIR__.'/../viewModels/modifyUsernameViewModel.php';

require_once __DIR__.'/../usecases/users/signup.php';
require_once __DIR__.'/../usecases/users/confirm.php';
require_once __DIR__.'/../usecases/login/login.php';
require_once __DIR__.'/../usecases/users/modifyEmail.php';
require_once __DIR__.'/../usecases/users/modifyUsername.php';


class Controller
{
	private static $instance;
	public $data_access;
	public $message_handler;
	public $output_view;
	public $presenter;
	
	private function __construct()
	{
		$this->data_access = new MockDataAccess("/var/www/html"); //TEMP
		$this->message_handler = new MockMessageHandler("/var/www/html"); //TEMP
	}

	public static function getInstance()
	{
		if (is_null(self::$instance))
		{
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function signup($user)
	{
		$this->output_view = new SignupViewModel();
		$this->presenter = new SignupPresenter(); 
		$inputData = new UserInputData($user, $this->data_access, $this->message_handler, $this->output_view, $this->presenter);
		SignupInteractor::run($inputData);
	}

	public function confirm($confirmation)
	{
		$this->output_view = new ConfirmViewModel();
		$this->presenter = new ConfirmPresenter();
		$inputData = new ConfirmInputData($confirmation, $this->data_access, $this->output_view, $this->presenter);
		ConfirmInteractor::run($inputData);
	}

	public function login($credentials)
	{
		$this->output_view = new LoginViewModel();
		$this->presenter = new LoginPresenter();
		$inputData = new LoginInputData($credentials, $this->data_access, $this->output_view, $this->presenter);
		LoginInteractor::run($inputData);
	}

	public function modifyEmail($user_id, $input)
	{
		$this->output_view = new modifyEmailViewModel();
		$this->presenter = new modifyEmailPresenter();
		$inputData = new ModifyEmailInputData($user_id, $input, $this->data_access, $this->output_view, $this->presenter);
		ModifyEmailInteractor::run($inputData);
	}

	public function modifyUsername($user_id, $input)
	{
		$this->output_view = new modifyUsernameViewModel();
		$this->presenter = new modifyUsernamePresenter();
		$inputData = new ModifyUsernameInputData($user_id, $input, $this->data_access, $this->output_view, $this->presenter);
		ModifyUsernameInteractor::run($inputData);
	}
}

/*
class Singleton
{
  private static $instance;

  private function __construct()
  {
    // Your "heavy" initialization stuff here
  }

  public static function getInstance()
  {
    if ( is_null( self::$instance ) )
    {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function someMethod1()
  {
    // whatever
  }

  public function someMethod2()
  {
    // whatever
  }
}*/

