<?php declare(strict_types=1);

namespace App\Validation;

use App\App;
use App\Exceptions\UnsupportedFileExtensionException;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Validator;

trait UploadImageableFileExtensionValidator
{
    /**
     * @param Validator $validator
     * @param array $files
     */
    protected function validateImageableFileExtension(Validator $validator, array $files): void
    {
        /** @var UploadedFile $file */
        foreach ($files as $file) {
            if ($this->isSupported($file) === false) {
                $validator->errors()->add('files',
                    (new UnsupportedFileExtensionException($file->guessExtension()))->getMessage()
                );
            }
        }
    }

    /**
     * @param UploadedFile $file
     * @return bool
     */
    protected function isSupported(UploadedFile $file): bool
    {
        return in_array(
            $file->guessExtension(), array_values(App::supportedImageableExtensions())
        );
    }
}
