<?php declare(strict_types=1);

namespace App\Components\Factories;

use App\App;
use App\Contracts\Components\Factories\ComponentableFactory as Contract;
use App\Exceptions\UndefinedFactoryException;
use App\Models\Componentables\Border;
use App\Models\Componentables\Button;
use App\Models\Componentables\Componentable;
use App\Models\Componentables\DynamicImage;
use App\Models\Componentables\Image;
use App\Models\Componentables\Shape;
use App\Models\Componentables\Text;

final class ComponentableFactory implements Contract
{
    /**
     * @var string[]
     */
    protected static $factories = [
        Text::class         => TextFactory::class,
        Shape::class        => ShapeFactory::class,
        Image::class        => ImageFactory::class,
        DynamicImage::class => DynamicImageFactory::class,
        Button::class       => ButtonFactory::class,
        Border::class       => BorderFactory::class,
    ];

    /**
     * @param string $type
     * @param array $values
     * @return Componentable
     */
    public function create(string $type, array $values): Componentable
    {
        $factory = self::factoryNameFor($type);

        return call_user_func([app($factory), 'create'], $values);
    }

    /**
     * @param string $type
     * @return string
     */
    public static function factoryNameFor(string $type): string
    {
        return self::factory(
            App::componentableClassBy($type)
        );
    }

    /**
     * @param string $type
     * @return string
     */
    public static function formArrayName(string $type): string
    {
        return (self::factory($type))::getArrayName();
    }

    /**
     * @param string $type
     * @return string
     */
    protected static function factory(string $type): string
    {
        if (array_key_exists($type, static::$factories) === false) {
            throw new UndefinedFactoryException($type);
        }

        return static::$factories[$type];
    }
}
