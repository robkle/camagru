<?php

require_once __DIR__.'/../usecases/interfaces/dataAccessInterface.php';

class MockDataAccess implements DataAccess
{
	public function fetchUser($user, $email): array
	{	
	}

	public function postUser($login, $email, $enc_pswd, $ckey): bool
	{
	}

	public function fetchCkey($ckey): array
	{
	}

	public function confirmUser($ckey): bool 
	{
	}
}
