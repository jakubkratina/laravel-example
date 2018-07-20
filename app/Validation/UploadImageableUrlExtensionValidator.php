<?php declare(strict_types=1);

namespace App\Validation;

use App\App;
use App\Exceptions\UnsupportedFileExtensionException;
use Illuminate\Validation\Validator;

trait UploadImageableUrlExtensionValidator
{
    /**
     * @param Validator $validator
     * @param string $extension
     */
    protected function validateImageableUrlExtension(Validator $validator, string $extension): void
    {
        if ($this->isSupported($extension) === false) {
            $validator->errors()->add('url',
                (new UnsupportedFileExtensionException($extension))->getMessage()
            );
        }
    }

    /**
     * @param string $extension
     * @return bool
     */
    protected function isSupported(string $extension): bool
    {
        return in_array($extension, array_values(App::supportedImageableExtensions()));
    }
}
