<?php declare(strict_types=1);

namespace App\Contracts\Components\Factories;

use App\Models\Componentables\Componentable;

interface ComponentableFactory
{
    /**
     * @param string $type
     * @param array  $values
     * @return Componentable
     */
    public function create(string $type, array $values): Componentable;

    /**
     * @param string $type
     * @return string
     */
    public static function factoryNameFor(string $type): string;

    /**
     * @param string $type
     * @return string
     */
    public static function formArrayName(string $type): string;
}
