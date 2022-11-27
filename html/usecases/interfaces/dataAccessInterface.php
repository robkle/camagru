<?php

interface DataAccess
{
	public function fetchUser($user, $email): array;
	public function postUser($login, $email, $enc_pswd, $ckey): bool;
	public function fetchCkey($ckey): array;
	public function confirmUser($ckey): bool;
}
