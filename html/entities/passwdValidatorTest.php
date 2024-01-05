<?php 

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
require __DIR__."/passwdValidator.php";

final class passwdValidatorTest extends TestCase
{
	public function test_passwd_format()//: void
	{
		$this->assertSame(1, passwdValidator::passwd_format("#Qwerty12345!"));
		$this->assertSame(1, passwdValidator::passwd_format("Qwerty12345"));
		$this->assertSame(0, passwdValidator::passwd_format("#qwerty12345!"));
		$this->assertSame(0, passwdValidator::passwd_format("#Qwerty!"));
		$this->assertSame(0, passwdValidator::passwd_format("#12345!"));
		$this->assertSame(0, passwdValidator::passwd_format("#Qw1"));
	}

	public function test_passwd_verify()
	{
		$pswd = "#Qwerty12345!";
		$enc_pswd = passwdValidator::passwd_encrypt($pswd);
		$this->assertSame(true, passwdValidator::passwd_verify($pswd, $enc_pswd));
		$this->assertSame(false, passwdValidator::passwd_verify("Qwerty12345", $enc_pswd));
		$this->assertSame(false, passwdValidator::passwd_verify(null, $enc_pswd));

	}
}
