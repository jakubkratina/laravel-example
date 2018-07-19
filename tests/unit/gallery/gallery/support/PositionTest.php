<?php

namespace Tests\Unit\Gallery\Support;

use App\Gallery\Support\Position;
use PHPUnit\Framework\TestCase;



class PositionTest extends TestCase
{

	/** @test */
	public function it_tests_attributes_values()
	{
		$x = 10;
		$y = 20;

		$position = new Position($x, $y);

		$this->assertSame($x, $position->x);
		$this->assertSame($y, $position->y);
	}
}
