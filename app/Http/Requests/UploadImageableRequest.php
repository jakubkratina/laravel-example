<?php declare(strict_types=1);

namespace App\Http\Requests\Api;

use App\Models\Project;
use App\Validation\UploadImageableFileExtensionValidator;
use Dingo\Api\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UploadImageableRequest extends FormRequest
{
    use UploadImageableFileExtensionValidator;

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        if ($this->user() === null) {
            return false;
        }

        if ($this->project_id !== null) {
            return $this->user()->ownsProject(
                Project::findOrFail($this->project_id)
            );
        }

        return true;
    }


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
