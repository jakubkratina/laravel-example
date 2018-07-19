<?php declare(strict_types=1);

namespace App\Support;

final class Dimensions
{
    /**
     * @var int
     */
    public $width;

    /**
     * @var int
     */
    public $height;

    /**
     * @param int $width
     * @param int $height
     */
    public function __construct($width, $height)
    {
        $this->width = (int) $width;
        $this->height = (int) $height;
    }

    /**
     * @param array $dimensions
     * @return Dimensions
     */
    public static function fromArray(array $dimensions): Dimensions
    {
        return new self($dimensions[0], $dimensions[1]);
    }

    /**
     * @param int $width
     * @param int $height
     * @return Dimensions
     */
    public static function create($width, $height): Dimensions
    {
        return new self($width, $height);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'width'  => $this->width,
            'height' => $this->height
        ];
    }

    /**
     * @return int
     */
    public function width(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function height(): int
    {
        return $this->height;
    }

    /**
     * @param int $width
     * @return Dimensions
     */
    public function setWidth(int $width): self
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @param int $height
     * @return Dimensions
     */
    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }
}
