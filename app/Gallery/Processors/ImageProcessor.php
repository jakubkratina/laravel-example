<?php declare(strict_types=1);

namespace App\Gallery\Processors;

use App\Contracts\Gallery\File;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Filesystem\Cloud;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Support\Collection;
use Intervention\Image\ImageManager;

final class ImageProcessor extends AbstractProcessor
{
    /**
     * @var ImageManager
     */
    protected $manager;

    /**
     * @var Cloud
     */
    protected $storage;

    /**
     * @param Factory $storage
     * @param ImageManager $manager
     */
    public function __construct(Factory $storage, ImageManager $manager)
    {
        parent::__construct($storage);

        $this->manager = $manager;
    }

    /**
     * @param File $file
     * @return Collection
     * @throws BindingResolutionException
     */
    public function store(File $file): Collection
    {
        $fileName = $this->path() . $file->hashName();

        $image = $this->saveImage($file->source(), $fileName);

        return collect()->push(
            $this->imageableFromFactory($fileName, $image->width(), $image->height())
        );
    }
}
