<?php

namespace Tests\Unit\Gallery\Processor;

use App\Gallery\Processors\PhotoshopProcessor;
use Illuminate\Contracts\Filesystem\Factory;
use Tests\TestCase;



final class PhotoshopProcessorTest extends TestCase
{

	/** @test */
	public function it_skips_first_layer()
	{
		$processor = new PhotoshopProcessor(app(Factory::class));

		$processor->skipComposite(true);

		$this->assertTrue($processor->shouldSkipComposite(0));
		$this->assertFalse($processor->shouldSkipComposite(1));
	}



	/** @test */
	public function it_does_not_skips_first_layer()
	{
		$processor = new PhotoshopProcessor(app(Factory::class));

		$processor->skipComposite(false);

		$this->assertFalse($processor->shouldSkipComposite(0));
		$this->assertFalse($processor->shouldSkipComposite(1));
	}
}
