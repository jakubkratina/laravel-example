<?php declare(strict_types=1);

namespace App\Contracts\Gallery;

use App\Gallery\Imageable;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

interface Manager
{
	/**
	 * @param UploadedFile $file
	 * @param User $user
	 * @return Collection|Imageable[]
	 */
	public function store(UploadedFile $file, User $user): Collection;
}
