<?php declare(strict_types=1);

namespace App\Contracts\Gallery;

use App\Gallery\Imageable;
use App\Models\User;
use Illuminate\Support\Collection;

interface Manager
{
    /**
     * @param File $file
     * @param User $user
     * @return Collection|Imageable[]
     */
    public function store(File $file, User $user): Collection;

    /**
     * @param  File $file
     * @return FileProcessable
     */
    public function processor(File $file): FileProcessable;
}
