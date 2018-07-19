<?php

namespace App\Gallery\Processors;

use App\App;
use App\Contracts\Gallery\Factory\ImageableFactory;
use App\Contracts\Gallery\Factory\PhotoshopFactory;
use App\Contracts\Gallery\FileProcessable;
use App\Exceptions\UndefinedFactoryException;
use App\Gallery\Imageable;
use App\Gallery\Support\Dimensions;
use App\Models\User;
use Illuminate\Contracts\Filesystem\Cloud;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as Intervention;
use Intervention\Image\Image;


abstract class AbstractProcessor implements FileProcessable
{

    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
     */
    protected $directory = 'gallery';

    /**
     * @var string[]
     */
    protected $factories = [
        'image'     => ImageableFactory::class,
        'photoshop' => PhotoshopFactory::class,
    ];

    /**
     * @var Cloud
     */
    protected $storage;


    /**
     * @param Factory $storage
     */
    public function __construct(Factory $storage)
    {
        $this->storage = $storage->disk();
    }


    /**
     * @param User $user
     * @return FileProcessable
     */
    public function forUser(User $user): FileProcessable
    {
        $this->user = $user;

        return $this;
    }


    /**
     * @param string $path
     * @param array ...$parameters
     * @return Imageable
     */
    protected function imageableFromFactory(string $path, ...$parameters): Imageable
    {
        $factory = app($this->resolveFactory());

        return $factory->build($path, ...$parameters);
    }


    /**
     * @return string
     */
    protected function path(): string
    {
        return sprintf('media/%s/%s/', $this->user->id, rtrim($this->directory, '/'));
    }


    /**
     * @throws UndefinedFactoryException
     * @return string
     */
    protected function resolveFactory(): string
    {
        $factory = Str::replaceFirst('processor', '', Str::lower(class_basename($this)));

        if (isset($this->factories[$factory]) === false) {
            throw new UndefinedFactoryException($factory);
        }

        return $this->factories[$factory];
    }


    /**
     * @param string $source
     * @param string $path
     * @return Image
     */
    protected function saveImage(string $source, string $path): Image
    {
        $image = Dimensions::resize(Intervention::make($source))->encode();

        $this->storage->put($path, (string) $image, App::visible());

        return $image;
    }
}
