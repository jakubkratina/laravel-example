<?php declare(strict_types=1);

namespace App\Gallery;

use App\App;
use App\Contracts\Gallery\FileProcessable;
use App\Contracts\Gallery\Manager as Contract;
use App\Exceptions\UnsupportedFileExtensionException;
use App\Gallery\Processors\ImageProcessor;
use App\Gallery\Processors\PhotoshopProcessor;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Manager;
use Illuminate\Support\Str;
use InvalidArgumentException;

final class ImageableManager extends Manager implements Contract
{
    /**
     * @param UploadedFile $file
     * @param User $user
     * @return Collection|Imageable[]
     */
    public function store(UploadedFile $file, User $user): Collection
    {
        return $this->driver($file)->forUser($user)->store($file);
    }

    /**
     * @param  string|UploadedFile|null $driver
     * @return FileProcessable
     */
    public function driver($driver = null): FileProcessable
    {
        // Let's keep a freedom to the developers. You can create a driver
        // with a driver string, you the default driver or let a manager resolve
        // the driver based on a uploaded file extension.
        $driver = $driver !== null
            ? ($driver instanceOf UploadedFile ? $this->resolve($driver) : $driver)
            : $this->getDefaultDriver();

        // If the given driver has not been created before, we will create the instances
        // here and cache it so we can return it next time very quickly. If there is
        // already a driver created by this name, we'll just return that instance.
        if (isset($this->drivers[$driver]) === false) {
            $this->drivers[$driver] = $this->createDriver($driver);
        }

        return $this->drivers[$driver];
    }

    /**
     * @param string $driver
     * @throws InvalidArgumentException
     * @return FileProcessable
     */
    public function createDriver($driver): FileProcessable
    {
        $method = 'create' . Str::studly($driver) . 'Driver';

        if (method_exists($this, $method) === true) {
            return $this->$method($this->config($driver));
        }

        throw new InvalidArgumentException("Driver [$driver] not supported.");
    }

    /**
     * @return FileProcessable
     */
    public function createImageDriver(): FileProcessable
    {
        return $this->app->make(ImageProcessor::class);
    }

    /**
     * @param array $config
     * @return FileProcessable
     */
    public function createPhotoshopDriver(array $config): FileProcessable
    {
        /** @var PhotoshopProcessor $processor */
        $processor = $this->app->make(PhotoshopProcessor::class);

        $processor->skipComposite($config['skipCompositeImage']);

        return $processor;
    }

    /**
     * @return string
     */
    public function getDefaultDriver(): string
    {
        throw new InvalidArgumentException('No Imageable driver was specified.');
    }

    /**
     * @param string $driver
     * @return array|null
     */
    protected function config(string $driver): ?array
    {
        return $this->app['config']['image.uploads.' . $driver];
    }

    /**
     * @param UploadedFile $file
     * @return string
     * @throws UnsupportedFileExtensionException
     */
    protected function resolve(UploadedFile $file): string
    {
        $extension = $file->guessExtension();

        if (in_array($extension, App::imageProcessorExtensions())) {
            return 'image';
        }

        if (App::isPhotoshopExtension($extension)) {
            return 'photoshop';
        }

        throw new UnsupportedFileExtensionException($extension);
    }
}
