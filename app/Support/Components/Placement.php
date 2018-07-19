<?php declare(strict_types=1);

namespace App\Support\Components;

use App\Support\Dimensions;
use App\Support\Position;
use UnexpectedValueException;

/**
 * @property Position $position
 * @property Dimensions $dimensions
 */
final class Placement
{
    /**
     * @var Position
     */
    protected $position;

    /**
     * @var Dimensions
     */
    protected $dimensions;

    /**
     * @param Position $position
     * @param Dimensions $dimensions
     */
    public function __construct(Position $position, Dimensions $dimensions)
    {
        $this->position = $position;
        $this->dimensions = $dimensions;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'width'      => $this->dimensions->width(),
            'height'     => $this->dimensions->height(),
            'position_x' => $this->position->x(),
            'position_y' => $this->position->y(),
        ];
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        if (in_array($name, ['position', 'dimensions']) === true) {
            return $this->{$name};
        }

        throw new UnexpectedValueException("$name not found on class");
    }
}
