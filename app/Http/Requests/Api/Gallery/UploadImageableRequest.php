<?php declare(strict_types=1);

namespace App\Http\Requests\Api\Gallery;

use App\Models\Project;
use Dingo\Api\Http\FormRequest;

abstract class UploadImageableRequest extends FormRequest
{
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
}
