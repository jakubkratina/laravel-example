<?php declare(strict_types=1);

namespace App\Gallery\Processors;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Filesystem\Cloud;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Http\UploadedFile;
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
     * @param UploadedFile $file
     * @return Collection
     * @throws BindingResolutionException
     */
    public function store(UploadedFile $file): Collection
    {
        $path = $this->path() . $this->fileName($file);

        $image = $this->saveImage($file->getRealPath(), $path);

        return collect()->push(
            $this->imageableFromFactory($path, $image->width(), $image->height())
        );
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    protected function fileName(UploadedFile $file): string
    {
        return time() . random_int(10, 99) . '_' . $file->hashName();
    }
}
