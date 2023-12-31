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
	public function changePassword($user_id, $enc_pswd): bool;
	public function changeUsername($user_id, $new_username): bool; 
	public function changeEmail($user_id, $new_email): bool;
	public function changeNotifications($user_id, $notifications): bool; 
	public function fetchImageInfo($image_id): array;
	public function postComment($image_id, $user_id, $comment): bool;
	public function fetchLike($image_id, $user_id): array;
	public function addLike($image_id, $user_id): bool;
	public function removeLike($image_id, $user_id): bool;
	public function fetchImages($creator = NULL);
	public function fetchImage($image_id): array;
	public function fetchComments($image_id);
	public function removeImageLikes($image_id): bool;
	public function removeComments($image_id): bool;
	public function removeImage($image_id, $user_id): bool;
}
