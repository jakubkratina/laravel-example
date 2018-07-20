<?php declare(strict_types=1);

namespace App\Http\Requests\Api\Gallery;

use App\Validation\UploadImageableFileExtensionValidator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

final class UploadImageableFileRequest extends UploadImageableRequest
{
    use UploadImageableFileExtensionValidator;

    /**
     * @return \Illuminate\Validation\Validator
     */
    public function validator(): \Illuminate\Validation\Validator
    {
        $validator = Validator::make($this->all(), [
            'files'      => 'required',
            'project_id' => [
                Rule::exists('projects', 'id')
            ]
        ]);

        $validator->after(function (\Illuminate\Validation\Validator $validator) {
            $this->validateImageableFileExtension($validator, $this->file('files'));
        });

        if ($validator->fails() === true) {
            $this->failedValidation($validator);
        }

        return $validator;
    }
}
