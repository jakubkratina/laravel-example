<?php declare(strict_types=1);

namespace App\Contracts\Gallery\Factory;

use App\Gallery\Imageable;

interface PhotoshopFactory
{
    /**
     * @param string $path
     * @param array $layer
     * @return Imageable
     */
    public function build(string $path, array $layer): Imageable;
}
