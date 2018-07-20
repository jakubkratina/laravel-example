<?php declare(strict_types=1);

namespace App\Gallery;

use App\Gallery\Support\Dimensions;
use App\Gallery\Support\Position;

final class Imageable
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var Position|null
     */
    private $position;

    /**
     * @var Dimensions|null
     */
    private $dimensions;

    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * @return int
     */
    public function width(): int
    {
        return $this->dimensions->width;
    }

    /**
     * @return int
     */
    public function height(): int
    {
        return $this->dimensions->height;
    }

    /**
     * @return Position|null
     */
    public function position(): ?Position
    {
        return $this->position;
    }

    /**
     * @return Dimensions|null
     */
    public function dimensions(): ?Dimensions
    {
        return $this->dimensions;
    }

    /**
     * @param int $x
     * @param int $y
     * @return $this
     */
    public function setPosition(int $x, int $y): self
    {
        $this->position = new Position($x, $y);

        return $this;
    }

    /**
     * @param int $width
     * @param int $height
     * @return $this
     */
    public function setDimensions(int $width, int $height): self
    {
        $this->dimensions = new Dimensions($width, $height);

        return $this;
    }
}
