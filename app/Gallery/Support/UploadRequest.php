<?php declare(strict_types=1);

namespace App\Gallery\Support;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Collection;

final class UploadRequest
{
    /**
     * @var Collection
     */
    private $files;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Project|null
     */
    private $project;

    /**
     * @var string
     */
    private $adapter;

    /**
     * @param array $files
     * @param User $user
     * @param Project|null $project
     * @param string $adapter
     */
    public function __construct(array $files, User $user, ?Project $project, string $adapter)
    {
        $this->files = new Collection($files);
        $this->user = $user;
        $this->project = $project;
        $this->adapter = $adapter;
    }

    /**
     * @return Collection
     */
    public function files(): Collection
    {
        return $this->files->map(function ($file) {
            return new $this->adapter($file);
        });
    }

    /**
     * @return User
     */
    public function user(): User
    {
        return $this->user;
    }

    /**
     * @return Project|null
     */
    public function project(): ?Project
    {
        return $this->project;
    }
}
