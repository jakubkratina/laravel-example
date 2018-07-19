<?php

namespace Tests\Acceptance\Api\Gallery;

use App\Models\Gallery\Image;
use Illuminate\Http\Request;
use Tests\Acceptance\Api\CanSendFileRequest;
use Tests\Acceptance\Api\Cleaning;
use Tests\AcceptanceTestCase;
use Tests\Support\UploadedFile;



class DeleteImageTest extends AcceptanceTestCase
{

	use ChecksForFileExistence;
	use CanSendFileRequest;
	use Cleaning;

	/**
	 * @var string
	 */
	protected $path = 'tests/fixtures/images/1.jpg';

	/**
	 * @var string
	 */
	protected $directory = 'app/public/';



	/** @test */
	public function it_deletes_an_file()
	{
		$response = $this->file(
			Request::METHOD_POST,
			'/gallery',
			[],
			['files' => [UploadedFile::makeFrom($this->path)]],
			$this->authorizationHeaders()
		);

		$json = $response->decodeResponseJson();

		$image = $json['data']['images']['data'][0];

		$user = Image::find($image['id'])->user;

		$this->checksForFileExistence($image['path']);

		$this->delete('/gallery/image/' . $image['id'], [], $this->authorizationHeaders())
			->assertStatus(204);

		$this->assertFileNotExists(storage_path($this->directory) . $image['path']);

		$this->deleteDirectoryFormGallery($user);
	}
}
