<?php declare(strict_types=1);

namespace Tests\Integration\Gallery\Processors;

use App\Gallery\Adapters\UploadedFileAdapter;
use App\Gallery\Imageable;
use App\Gallery\Processors\PhotoshopProcessor;
use App\Models\User;
use Illuminate\Contracts\Filesystem\Factory;
use Tests\Acceptance\Api\Cleaning;
use Tests\Support\UploadedFile;
use Tests\TestCase;

final class PhotoshopProcessorTest extends TestCase
{
    use Cleaning;

    /**
     * @var string
     */
    protected $path = 'tests/fixtures/images/psds/1.psd';

    /**
     * @var array
     */
    protected $images = [
        0 => [
            'position'   => [
                'x' => 0,
                'y' => 0,
            ],
            'dimensions' => [
                'width'  => 1000,
                'height' => 1000,
            ],
        ],
        1 => [
            'position'   => [
                'x' => 278,
                'y' => 266,
            ],
            'dimensions' => [
                'width'  => 438,
                'height' => 433,
            ],
        ],
    ];

    /** @test */
    public function it_creates_files_and_imageables_from_psd_and_returns_collection_of_imageables(): void
    {
        $user = factory(User::class)->create();

        $collection = (new PhotoshopProcessor(app(Factory::class)))
            ->skipComposite(true)
            ->forUser($user)
            ->store(new UploadedFileAdapter(UploadedFile::makeFrom($this->path)));

        for ($i = 0; $i < 2; $i++) {
            $this->assertImage($this->images[$i], $collection->get($i));
        }

        $this->deleteDirectoryFormGallery($user);
    }

    /**
     * @param array $expected
     * @param Imageable $imageable
     */
    protected function assertImage(array $expected, Imageable $imageable): void
    {
        $this->assertFileExists(storage_path() . '/app/public/' . $imageable->path());

        $this->assertSame($expected['position']['x'], $imageable->position()->x);
        $this->assertSame($expected['position']['y'], $imageable->position()->y);

        $this->assertSame($expected['dimensions']['width'], $imageable->dimensions()->width);
        $this->assertSame($expected['dimensions']['height'], $imageable->dimensions()->height);
    }
}
