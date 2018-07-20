<?php declare(strict_types=1);

namespace App\Http\Requests\Api\Gallery;

use App\Validation\UploadImageableUrlExtensionValidator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

final class UploadImageableUrlRequest extends UploadImageableRequest
{
    use UploadImageableUrlExtensionValidator;

    /**
     * @return \Illuminate\Validation\Validator
     */
    public function validator(): \Illuminate\Validation\Validator
    {
        $validator = Validator::make($this->all(), [
            'url'        => 'required|string',
            'project_id' => [
                Rule::exists('projects', 'id')
            ]
        ]);

        $validator->after(function (\Illuminate\Validation\Validator $validator) {
            if ($this->has('url')) {
                $this->validateImageableUrlExtension($validator, pathinfo($this->get('url'), PATHINFO_EXTENSION));
            }
        });

        if ($validator->fails() === true) {
            $this->failedValidation($validator);
        }

        return $validator;
    }
}
