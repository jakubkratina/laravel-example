<?php declare(strict_types=1);

namespace App\Gallery\Adapters;

use App\Contracts\Gallery\File;
use Illuminate\Http\UploadedFile;

final class UploadedFileAdapter implements File
{
    /**
     * @var UploadedFile
     */
    private $file;

    /**
     * @param UploadedFile $file
     */
    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function path(): string
    {
        return $this->file->getRealPath();
    }

    /**
     * @return string
     */
    public function extension(): string
    {
        return $this->file->guessExtension();
    }

    /**
     * @return string
     */
    public function hashName(): string
    {
        return time() . random_int(10, 99) . '_' . $this->file->hashName();
    }

    /**
     * @return string
     */
    public function source(): string
    {
        return $this->file->getRealPath();
    }
}
