<?php

namespace Tests\Acceptance\Api\Gallery;

use App\Exceptions\UnsupportedFileExtensionException;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Tests\Acceptance\Api\CanSendFileRequest;
use Tests\AcceptanceTestCase;



class UnsupportedFileExtensionTest extends AcceptanceTestCase
{

	use CanSendFileRequest;

	/**
	 * @var string
	 */
	protected $fontPath = 'tests/fixtures/fonts/test-font.ttf';



	/** @test */
	public function it_sends_the_wrong_file_format_and_expects_an_error_message()
	{
		$response = $this->file(
			Request::METHOD_POST,
			'/gallery',
			[],
			['files' => [$this->fileWithNotSupportedExtension()]],
			$this->authorizationHeaders()
		);

		$response->assertJson([
			'errors' => [
				'files' => [
					(new UnsupportedFileExtensionException('ttf'))->getMessage()
				]
			]
		]);
	}



	/**
	 * @return UploadedFile
	 */
	protected function fileWithNotSupportedExtension()
	{
		$path = base_path($this->fontPath);
		$filename = str_slug(str_random());
		$mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);

		return new UploadedFile($path, $filename, $mime, null, null, true);
	}

}
