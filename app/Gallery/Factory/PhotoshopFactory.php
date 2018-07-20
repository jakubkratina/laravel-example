<?php declare(strict_types=1);

namespace App\Gallery\Factory;

use App\Contracts\Gallery\Factory\PhotoshopFactory as Contract;
use App\Gallery\Imageable;

final class PhotoshopFactory implements Contract
{
    /**
     * @param string $path
     * @param array $layer
     * @return Imageable
     */
    public function build(string $path, array $layer): Imageable
    {
        return (new Imageable($path))
            ->setPosition($layer['x'], $layer['y'])
            ->setDimensions($layer['width'], $layer['height']);
    }
}
