<?php

namespace Tests\Integration\Gallery\Processors;

use App\Gallery\Processors\ImageProcessor;
use App\Models\User;
use File;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Support\Collection;
use Intervention\Image\ImageManager;
use Tests\Acceptance\Api\Cleaning;
use Tests\Support\UploadedFile;
use Tests\TestCase;



final class ImageProcessorTest extends TestCase
{

	use Cleaning;

	/**
	 * @var string
	 */
	protected $path = 'tests/fixtures/images/1.jpg';



	/** @test */
	public function it_creates_file_and_imageable_and_returns_collection()
	{
		[$width, $height] = getimagesize(base_path($this->path));

		$user = factory(User::class)->create();
		$processor = (new ImageProcessor(app(Factory::class), new ImageManager));

		/** @var Collection $collection */
		$collection = $processor
			->forUser($user)
			->store(UploadedFile::makeFrom($this->path));

		$this->assertFileExists(storage_path() . '/app/public/' . $collection->first()->path());

		$this->assertSame($width, $collection->first()->dimensions()->width);
		$this->assertSame($height, $collection->first()->dimensions()->height);

		// Directory clean up
		File::deleteDirectory(storage_path() . '/app/public/media/' . $user->id);
	}
}
