<?php declare(strict_types=1);

namespace App\Gallery;

use App\App;
use App\Contracts\Gallery\File;
use App\Contracts\Gallery\FileProcessable;
use App\Contracts\Gallery\Manager as Contract;
use App\Exceptions\UnsupportedFileExtensionException;
use App\Gallery\Processors\ImageProcessor;
use App\Gallery\Processors\PhotoshopProcessor;
use App\Models\User;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;

final class ImageableManager implements Contract
{
    /**
     * @var Repository
     */
    private $config;

    /**
     * @var string[]
     */
    private $processors = [];

    /**
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * @param File $file
     * @param User $user
     * @return Collection|Imageable[]
     */
    public function store(File $file, User $user): Collection
    {
        return $this->processor($file)->forUser($user)->store($file);
    }

    /**
     * @param  File $file
     * @return FileProcessable
     */
    public function processor(File $file): FileProcessable
    {
        $processor = $this->resolveProcessorName($file);

        if (isset($this->processors[$processor]) === false) {
            $this->processors[$processor] = $this->createProcessor($processor);
        }

        return $this->processors[$processor];
    }

    /**
     * @param string $processor
     * @throws InvalidArgumentException
     * @return FileProcessable
     */
    public function createProcessor(string $processor): FileProcessable
    {
        $method = 'create' . Str::studly($processor) . 'Processor';

        if (method_exists($this, $method) === true) {
            return $this->$method($this->config($processor));
        }

        throw new InvalidArgumentException("Processor [$processor] not supported.");
    }

    /**
     * @return FileProcessable
     */
    public function createImageProcessor(): FileProcessable
    {
        return app(ImageProcessor::class);
    }

    /**
     * @param array $config
     * @return FileProcessable
     */
    public function createPhotoshopProcessor(array $config): FileProcessable
    {
        return tap(app(PhotoshopProcessor::class), function (PhotoshopProcessor $processor) use ($config) {
            $processor->skipComposite($config['skipCompositeImage']);
        });
    }

    /**
     * @param string $processor
     * @return array|null
     */
    protected function config(string $processor): ?array
    {
        return $this->config->get('image.uploads.' . $processor);
    }

    /**
     * @param File $file
     * @return string
     * @throws UnsupportedFileExtensionException
     */
    protected function resolveProcessorName(File $file): string
    {
        $extension = $file->extension(); // TODO UploadedFile => guessExtension

        if (in_array($extension, App::imageProcessorExtensions())) {
            return 'image';
        }

        if (App::isPhotoshopExtension($extension)) {
            return 'photoshop';
        }

        throw new UnsupportedFileExtensionException($extension);
    }
}
