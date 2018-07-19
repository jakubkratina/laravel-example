<?php declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\Models\Component;
use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;

interface ComponentRepository
{
    /**
     * @param Project $project
     * @param array   $values
     * @return Component
     */
    public function create(Project $project, array $values): Component;

    /**
     * @param Project $project
     * @return int
     */
    public function maxOrder(Project $project): int;

    /**
     * @param Project $project
     * @param string  $type
     * @return string
     */
    public function generateNameFor(Project $project, $type): string;

    /**
     * @param Project $project
     * @return Collection
     */
    public function visible(Project $project): Collection;
}
