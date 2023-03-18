<?php

interface DataAccess
{
	public function fetchUser($userId, $user, $email): array;
	public function postUser($login, $email, $enc_pswd, $ckey): bool;
	public function fetchCkey($ckey): array;
	public function confirmUser($ckey): bool;
	public function postImage($userId, $image): bool;
	public function postRequestToken($email, $token, $timeout): bool;
	public function fetchRequestToken($token): array;
	public function deleteRequestToken($email): bool;
}
