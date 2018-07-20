<?php declare(strict_types=1);

namespace App\Gallery\Processors;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Imagick;

final class PhotoshopProcessor extends AbstractProcessor
{
    /**
     * @var bool
     */
    protected $skipComposite;

    /**
     * @var string
     */
    protected $extension = 'png';

    /**
     * @param UploadedFile $file
     * @return Collection
     * @throws \ImagickException
     * @throws BindingResolutionException
     */
    public function store(UploadedFile $file): Collection
    {
        $imageables = new Collection();

        $imagick = new Imagick($file->path());

        // Only the Imagick can process a photoshop documents.
        // We want to save each layer as an image to the storage.
        // The Imagick is array able, so we can iterate through
        // the imagick to get an access to the all layers.
        foreach ($imagick as $i => $layer) {

            // The very first layer we get is an composite image.
            // This image is make of all layers in the psd file,
            // as you can see in the Photoshop itself.
            // In most use cases we don't want to save this image.
            if ($this->shouldSkipComposite($i)) {
                continue;
            }

            // To get an access to the layer, we have to set this
            // iterator. After that we can make method calls like
            // getImagePage() to get the layer information,
            // like the layer position or the layer dimensions.
            $imagick->setIteratorIndex($i);
            $imagick->setImageFormat('png');

            $path = $this->path() . $this->createFileName();

            $this->saveImage($imagick->getImageBlob(), $path);

            $imageables->push(
                $this->imageableFromFactory($path, $imagick->getImagePage())
            );
        }

        return $imageables;
    }

    /**
     * @param bool $bool
     * @return self
     */
    public function skipComposite(bool $bool): self
    {
        $this->skipComposite = $bool;

        return $this;
    }

    /**
     * @param int $i
     * @return bool
     */
    public function shouldSkipComposite(int $i): bool
    {
        return $this->skipComposite === true && $i === 0;
    }

    /**
     * @return string
     */
    protected function createFileName(): string
    {
        return time() . '_' . str_slug(str_random(64)) . '.' . $this->extension;
    }
}
