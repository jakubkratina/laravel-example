<?php declare(strict_types=1);

namespace App\Contracts\Gallery;

use App\Gallery\Imageable;
use App\Models\User;
use Illuminate\Support\Collection;

interface FileProcessable
{
    /**
     * @param File $file
     * @return Collection|Imageable[]
     */
    public function store(File $file): Collection;

    /**
     * @param User $user
     * @return FileProcessable
     */
    public function forUser(User $user): FileProcessable;
}
