<?php

namespace Tests\Acceptance\Api\Gallery;

use App\Models\Component;
use App\Models\Gallery\Image;
use Illuminate\Http\Request;
use Tests\Acceptance\Api\CanCreateProject;
use Tests\Acceptance\Api\CanSendFileRequest;
use Tests\Acceptance\Api\Cleaning;
use Tests\AcceptanceTestCase;
use Tests\Support\UploadedFile;



final class ImageUploadTest extends AcceptanceTestCase
{

	use ChecksForFileExistence;
	use CanCreateProject;
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
	public function it_uploads_image_file()
	{
		$response = $this->file(
			Request::METHOD_POST,
			'/gallery',
			[],
			['files' => [UploadedFile::makeFrom($this->path)]],
			$this->authorizationHeaders()
		);

		$image = $response->decodeResponseJson()['data']['images']['data'][0];

		$this->assertDatabaseHas('user_gallery', [
			'id'   => $image['id'],
			'path' => $this->stripDomain($image['path'])
		]);

		$response->assertJsonStructure([
			'data' => [
				'images' => [
					'data' => [
						[
							'id',
							'path'
						]
					]
				]
			]
		]);

		$this->checksForFileExistence($image['path']);

		$this->deleteUserGalleryImageOrDirectory(Image::find($image['id']));
	}



	/** @test */
	public function it_uploads_image_file_to_the_project()
	{
		$project = $this->createProject();

		$response = $this->file(
			Request::METHOD_POST,
			'/gallery',
			['project_id' => $project->id],
			['files' => [UploadedFile::makeFrom($this->path)]],
			$this->authorizationHeaders()
		);

		$image = $response->decodeResponseJson()['data']['images']['data'][0];
		$component = $response->decodeResponseJson()['data']['components']['data'][0];

		$this->assertDatabaseHas('user_gallery', [
			'id'   => $image['id'],
			'path' => $this->stripDomain($image['path'])
		]);

		$this->assertDatabaseHas('componentable_images', [
			'path' => $this->stripDomain($component['componentable']['path'])
		]);

		$response->assertJsonStructure([
			'data' => [
				'images'     => [
					'data' => [
						[
							'id',
							'path'
						]
					]
				],
				'components' => [
					'data' => [
						[
							'id',
							'visibility',
							'position_x',
							'position_y',
							'height',
							'width',
							'order',
							'componentable_type',
							'componentable' => [
								'path'
							]
						]
					]
				]
			]
		]);

		$this->checksForFileExistence($image['path']);
		$this->checksForFileExistence($component['componentable']['path']);

		$this->deleteUserGalleryImageOrDirectory(Image::find($image['id']));
		$this->deleteComponentImageOrDirectory(Component::find($component['id']));
	}
}
