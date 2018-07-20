<?php declare(strict_types=1);

namespace App\Contracts\Gallery\Factory;

use App\Gallery\Imageable;

interface ImageableFactory
{
    /**
     * @param string $path
     * @param int $width
     * @param int $height
     * @return Imageable
     */
    public function build(string $path, $width, $height): Imageable;
}
