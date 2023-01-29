<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__.'/tokenHandler.php';

final class tokenHandlerTest extends TestCase
{
	public function testTokenCompare()
	{
		$a = random_bytes(32);
		$b = random_bytes(24);
		$this->assertTrue(Tokens::tokenCompare($a, $a));		
		$this->assertFalse(Tokens::tokenCompare($a, $b));		
	}
}
