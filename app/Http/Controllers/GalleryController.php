<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\App;
use App\Contracts\Interactions\Gallery\UploadImageableFile;
use App\Http\Requests\Api\UploadImageableRequest;
use App\Models\Project;
use App\Transformers\Gallery\ImageTransformer;
use App\Transformers\Gallery\ResponseCollectionTransformer;
use Dingo\Api\Exception\ResourceException;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

final class GalleryController extends ApiController
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return $this->response->collection(
            $this->user->gallery, new ImageTransformer
        );
    }

    /**
     * @param UploadImageableRequest $request
     * @throws ResourceException
     * @return Response
     */
    public function store(UploadImageableRequest $request): Response
    {
        $collection = App::interact(UploadImageableFile::class, [
            $request->all(), $this->user, $this->project($request)
        ]);

        return $this->response->item($collection, new ResponseCollectionTransformer);
    }

    /**
     * @param Request $request
     * @return Project|null
     */
    protected function project(Request $request): ?Project
    {
        return Project::find($request->get('project_id'));
    }
}
