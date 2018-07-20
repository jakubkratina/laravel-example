<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\App;
use App\Contracts\Interactions\Gallery\UploadImageableFile;
use App\Gallery\Adapters\UploadedFileAdapter;
use App\Gallery\Adapters\UrlFileAdapter;
use App\Gallery\Support\UploadRequest;
use App\Gallery\Support\UploadResponse;
use App\Http\Requests\Api\Gallery\UploadImageableFileRequest;
use App\Http\Requests\Api\Gallery\UploadImageableUrlRequest;
use App\Models\Project;
use App\Transformers\Gallery\ImageTransformer;
use App\Transformers\Gallery\ResponseCollectionTransformer;
use Dingo\Api\Exception\ResourceException;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;

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
     * @param UploadImageableFileRequest $request
     * @throws ResourceException
     * @return Response
     */
    public function files(UploadImageableFileRequest $request): Response
    {
        return $this->response->item($this->upload(
            $request, $request->file('files'), UploadedFileAdapter::class
        ), new ResponseCollectionTransformer);
    }

    /**
     * @param UploadImageableUrlRequest $request
     * @return Response
     */
    public function url(UploadImageableUrlRequest $request): Response
    {
        return $this->response->item($this->upload(
            $request, [$request->get('url')], UrlFileAdapter::class
        ), new ResponseCollectionTransformer);
    }

    /**
     * @param Request $request
     * @return Project|null
     */
    private function project(Request $request): ?Project
    {
        return Project::find($request->get('project_id'));
    }

    /**
     * @param Request $request
     * @param array $files
     * @param string $adapter
     * @return UploadResponse
     */
    private function upload(Request $request, array $files, string $adapter): UploadResponse
    {
        return App::interact(UploadImageableFile::class, [new UploadRequest(
            $files, $this->user, $this->project($request), $adapter
        )]);
    }
}
