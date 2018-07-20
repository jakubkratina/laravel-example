<?php declare(strict_types=1);

namespace App\Interactions\Gallery;

use App\Contracts\Gallery\Manager;
use App\Contracts\Interactions\Components\CreateComponent;
use App\Contracts\Interactions\Gallery\UploadImageableFile as Contract;
use App\Contracts\Repositories\Gallery\ImageRepository;
use App\Gallery\Imageable;
use App\Gallery\ImageComponentArrayBuilder;
use App\Gallery\Support\UploadRequest;
use App\Gallery\Support\UploadResponse;
use App\Models\Gallery\Image;
use Illuminate\Support\Collection;

final class UploadImageableFile implements Contract
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var ImageRepository
     */
    protected $images;

    /**
     * @var UploadResponse
     */
    protected $response;

    /**
     * @param Manager $manager
     * @param ImageRepository $images
     */
    public function __construct(
        Manager $manager,
        ImageRepository $images
    )
    {
        $this->manager = $manager;
        $this->images = $images;

        $this->response = new UploadResponse;
    }

    /**
     * @param UploadRequest $request
     * @return UploadResponse
     * @throws \Exception
     */
    public function handle(UploadRequest $request): UploadResponse
    {
        foreach ($request->files() as $file) {
            $this->addToResponse(
                $this->manager->store($file, $request->user()), $request
            );
        }

        return $this->response;
    }

    /**
     * @param Image $image
     * @param Imageable $imageable
     * @throws \Exception
     */
    protected function createImageComponent(Image $image, Imageable $imageable): void
    {
        $this->response->addComponent(
            app(CreateComponent::class)->handle(
                ImageComponentArrayBuilder::build($image, $imageable)
            )
        );
    }

    /**
     * @param Collection $imageables
     * @param UploadRequest $request
     * @throws \Exception
     */
    protected function addToResponse(Collection $imageables, UploadRequest $request): void
    {
        foreach ($imageables as $imageable) {
            $this->response->addImage(
                $image = $this->images->create($imageable, $request->user(), $request->project())
            );

            if ($image->project !== null) {
                $this->createImageComponent($image, $imageable);
            }
        }
    }
}
