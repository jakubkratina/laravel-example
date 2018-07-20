<?php declare(strict_types=1);

namespace App\Gallery\Factory;

use App\Contracts\Gallery\Factory\ImageableFactory as Contract;
use App\Gallery\Imageable;

final class ImageableFactory implements Contract
{
    /**
     * @param string $path
     * @param int $width
     * @param int $height
     * @return Imageable
     */
    public function build(string $path, $width, $height): Imageable
    {
        return (new Imageable($path))
            ->setDimensions($width, $height);
    }
}
