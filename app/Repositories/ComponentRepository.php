<?php

namespace App\Repositories;

use App\Contracts\Repositories\ComponentRepository as Contract;
use App\Models\Component;
use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;



final class ComponentRepository implements Contract
{

	/**
	 * @var Project
	 */
	protected $projects;



	/**
	 * @param Project $projects
	 */
	public function __construct(Project $projects)
	{
		$this->projects = $projects;
	}



	/**
	 * {@inheritdoc}
	 */
	public function create(Project $project, array $values): Component
	{
		$this->increaseOrder($project);

		$values = array_merge($values, [
			'order'        => 1,
			'angle'        => 0.0,
			'transparency' => 0,
			'name'         => $this->generateNameFor($project, $values['type'])
		]);

		return $project->components()->create($values);
	}



	/**
	 * {@inheritdoc}
	 */
	public function maxOrder(Project $project): int
	{
		return DB::table('components')->where('project_id', $project->id)->max('order') + 1;
	}



	/**
	 * {@inheritdoc}
	 */
	public function generateNameFor(Project $project, $type): string
	{
		$components = $project->components()->where('componentable_type', $type)->count();

		return sprintf('%s %d', Str::ucfirst($type), $components + 1);
	}



	/**
	 * @param Project $project
	 * @return Collection
	 */
	public function visible(Project $project): Collection
	{
		return $project->components()
			->visible()
			->orderBy('order')
			->get();
	}



	/**
	 * @param Project $project
	 */
	protected function increaseOrder(Project $project)
	{
		DB::table('components')->where('project_id', $project->id)->increment('order');
	}
}
