<?php declare(strict_types=1);

namespace App\Support\Components;

use App\App;
use App\Contracts\Project\Type;
use App\Models\Componentables\Border;
use App\Models\Componentables\Componentable;
use App\Models\Componentables\Image;
use App\Models\Project;
use App\Support\Dimensions;
use App\Support\Position;

final class PlacementFactory
{
    /**
     * @param Project $project
     * @param Componentable $componentable
     * @param array|null $data
     * @return Placement
     */
    public static function create(Project $project, Componentable $componentable, array $data = null): Placement
    {
        $dimensions = self::dimensions($componentable, $project->type);

        $position = [
            'x' => $data['position_x'] ?? floor(($project->type->width - $dimensions->width()) / 2),
            'y' => $data['position_y'] ?? floor(($project->type->height - $dimensions->height()) / 2)
        ];

        return new Placement(
            Position::create(round($position['x']), round($position['y'])), $dimensions
        );
    }

    /**
     * @param Componentable $componentable
     * @param Type $type
     * @return Dimensions
     */
    protected static function dimensions(Componentable $componentable, Type $type): Dimensions
    {
        if ($componentable instanceof Image) {
            if ($componentable->width < $type->width && $componentable->height < $type->height) {
                return Dimensions::create($componentable->width, $componentable->height);
            }

            if ($componentable->width >= $componentable->height) {
                return self::proportionallyResizedDimensions('width', 'height', $componentable, $type);
            }

            return self::proportionallyResizedDimensions('height', 'width', $componentable, $type);
        }

        if ($componentable instanceof Border) {
            return Dimensions::create($type->width, $type->height);
        }

        return App::dimensionsFor(get_class($componentable));
    }

    /**
     * @param string $primary
     * @param string $secondary
     * @param Componentable $componentable
     * @param Type $type
     * @return Dimensions
     */
    protected static function proportionallyResizedDimensions(string $primary, string $secondary, Componentable $componentable, Type $type): Dimensions
    {
        $dimensions = [];

        // We have to ensure that an resized image primary dimension is not bigger than type itself
        $dimensions[$primary] = min($componentable->$primary, $type->$primary);

        // Then we need to calculate the ratio to proportionally resize the image
        $ratio = $dimensions[$primary] / $componentable->$primary;

        // Apply the ration to the secondary dimension
        $dimensions[$secondary] = $componentable->$secondary * $ratio;

        // And return the dimensions as an object
        return Dimensions::create($dimensions['width'], $dimensions['height']);
    }
}
