<?php declare(strict_types=1);

namespace App\Contracts\Interactions\Gallery;

use App\Gallery\Support\UploadRequest;
use App\Gallery\Support\UploadResponse;

interface UploadImageableFile
{
    /**
     * @param UploadRequest $request
     * @return UploadResponse
     */
    public function handle(UploadRequest $request): UploadResponse;
}
