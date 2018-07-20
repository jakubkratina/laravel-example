<?php declare(strict_types=1);

namespace App\Gallery\Support;

use App\Models\Component;
use App\Models\Gallery\Image;
use Illuminate\Support\Collection;

final class UploadResponse
{
    /**
     * @var Collection
     */
    private $images;

    /**
     * @var Collection
     */
    private $components;

    public function __construct()
    {
        $this->images = new Collection();
        $this->components = new Collection();
    }

    /**
     * @param Image $image
     */
    public function addImage(Image $image): void
    {
        $this->images->push($image);
    }

    /**
     * @param Component $component
     */
    public function addComponent(Component $component): void
    {
        $this->components->push($component);
    }

    /**
     * @return Collection
     */
    public function images(): Collection
    {
        return $this->images;
    }

    /**
     * @return Collection
     */
    public function components(): Collection
    {
        return $this->components;
    }
}
