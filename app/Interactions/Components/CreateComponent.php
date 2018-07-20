<?php

namespace App\Interactions\Components;

use App\Contracts\Components\Factories\ComponentableFactory;
use App\Contracts\Interactions\Components\CreateComponent as Contract;
use App\Contracts\Repositories\ComponentRepository;
use App\Models\Component;
use App\Models\Componentables\Componentable;
use App\Models\Project;
use App\Support\Components\PlacementFactory;
use Closure;

final class CreateComponent implements Contract
{
    /**
     * @var ComponentRepository
     */
    protected $components;

    /**
     * @var Project
     */
    protected $projects;

    /**
     * @var ComponentableFactory
     */
    protected $factory;

    /**
     * @param ComponentRepository  $components
     * @param Project              $projects
     * @param ComponentableFactory $factory
     */
    public function __construct(
        ComponentRepository $components,
        Project $projects,
        ComponentableFactory $factory
    ) {
        $this->components = $components;
        $this->projects = $projects;
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(array $data): Component
    {
        $componentable = $this->createComponentable($data);

        $componentable->component()->save(
            $component = $this->createComponent($data, function (Project $project) use ($componentable, $data) {
                return PlacementFactory::create($project, $componentable, $data)->toArray();
            })
        );

        $componentable->save();

        // component visibility was null, the fresh() method solved this problem
        return $component->fresh();
    }

    /**
     * @param array $data
     * @return Componentable
     */
    protected function createComponentable(array $data): Componentable
    {
        return $this->factory->create(
            $data['type'], $data['componentable'] ?? []
        );
    }

    /**
     * @param array   $values
     * @param Closure $callback
     * @return Component
     */
    protected function createComponent(array $values, Closure $callback): Component
    {
        $project = $this->projects->find($values['project_id']);

        // Use this callback to merge an additional values for the component
        $values = array_merge($values, $callback($project));

        return $this->components->create($project, $values);
    }
}
