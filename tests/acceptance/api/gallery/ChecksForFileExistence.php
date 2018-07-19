<?php

namespace Tests\Acceptance\Api\Gallery;


use Tests\Acceptance\Api\CanWorkWithFiles;



trait ChecksForFileExistence
{

	use CanWorkWithFiles;



	/**
	 * @param string $path
	 */
	protected function checksForFileExistence(string $path)
	{
		$this->assertFileExists(storage_path($this->directory) . $this->stripDomain($path));
	}
}
