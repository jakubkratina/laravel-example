<?php declare(strict_types=1);

namespace Tests\Integration\Gallery;

use App\Gallery\Adapters\UploadedFileAdapter;
use App\Gallery\ImageableManager;
use App\Gallery\Processors\ImageProcessor;
use App\Gallery\Processors\PhotoshopProcessor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;

final class ImageableManagerTest extends TestCase
{
    /**
     * @var ImageableManager
     */
    protected $manager;

    /**
     * @var string
     */
    protected $directories = [
        'jpeg' => 'tests/fixtures/images/',
        'psd'  => 'tests/fixtures/images/psds/',
        'pdf'  => 'tests/fixtures/pdfs/',
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->manager = app(ImageableManager::class);
    }

    /** @test */
    public function it_returns_photoshop_processor(): void
    {
        $processor = $this->manager->processor(new UploadedFileAdapter($this->file('psd')));

        $this->assertInstanceOf(PhotoshopProcessor::class, $processor);
    }

    /** @test */
    public function it_returns_image_processor(): void
    {
        $processor = $this->manager->processor(new UploadedFileAdapter($this->file('jpeg')));

        $this->assertInstanceOf(ImageProcessor::class, $processor);
    }

    /**
     * @expectedException \App\Exceptions\UnsupportedFileExtensionException
     * @test
     */
    public function it_fails_on_not_supported_file_extension(): void
    {
        $this->manager->processor(new UploadedFileAdapter($this->file('pdf')));
    }

    /**
     * @param string $type
     * @return UploadedFile
     */
    protected function file(string $type): UploadedFile
    {
        $path = $this->path($type);

        return new UploadedFile(
            $path, str_slug(str_random()), finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path), null, null, true
        );
    }

    /**
     * @param string $type
     * @return string
     */
    protected function directory(string $type): string
    {
        return $this->directories[$type];
    }

    /**
     * @param string $type
     * @return string
     */
    protected function path(string $type): string
    {
        return base_path(
            $this->directory($type) . $this->filename($type)
        );
    }

    /**
     * @param string $type
     * @return string
     */
    protected function filename(string $type): string
    {
        $filename = '1.' . $type;

        if (Str::contains($type, 'jpeg')) {
            $filename = Str::replaceFirst('jpeg', 'jpg', $filename);
        }

        return $filename;
    }
}
