<?php

namespace Tests\Unit\Gallery\Support;

use App\Gallery\Support\ResponseCollection;
use App\Models\Component;
use App\Models\Gallery\Image;
use PHPUnit\Framework\TestCase;



final class ResponseCollectionTest extends TestCase
{

	/** @test */
	public function it_adds_a_image()
	{
		$responseCollection = new ResponseCollection;

		$responseCollection->addImage($image = new Image);

		$this->assertSame($image, $responseCollection->images()->first());
	}



	/** @test */
	public function it_adds_a_component()
	{
		$responseCollection = new ResponseCollection;

		$responseCollection->addComponent($component = new Component);

		$this->assertSame($component, $responseCollection->components()->first());
	}



	/** @test */
	public function it_retrieves_all_images()
	{
		$responseCollection = new ResponseCollection;

		$numberOfImages = 3;

		$images = [];

		for ($i = 1; $i <= $numberOfImages; $i++) {
			$responseCollection->addImage($image = new Image());

			$images[] = $image;
		}

		$this->assertSame(
			$numberOfImages, $responseCollection->images()->count()
		);

		foreach ($responseCollection->images() as $key => $image) {
			$this->assertSame($images[$key], $image);
		}
	}



	/** @test */
	public function it_retrieves_all_components()
	{
		$responseCollection = new ResponseCollection;

		$numberOfComponents = 3;

		$components = [];

		for ($i = 1; $i <= $numberOfComponents; $i++) {
			$responseCollection->addComponent($component = new Component);
			$components[] = $component;
		}

		$this->assertSame(
			$numberOfComponents,
			$responseCollection->components()->count()
		);

		foreach ($responseCollection->components() as $key => $component) {
			$this->assertSame($components[$key], $component);
		}
	}

}
