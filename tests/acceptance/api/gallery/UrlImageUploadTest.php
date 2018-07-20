<?php declare(strict_types=1);

namespace Tests\Acceptance\Api\Gallery;

use App\Models\Component;
use App\Models\Gallery\Image;
use Tests\Acceptance\Api\CanCreateProject;
use Tests\Acceptance\Api\Cleaning;
use Tests\AcceptanceTestCase;

final class UrlImageUploadTest extends AcceptanceTestCase
{
    use ChecksForFileExistence;
    use CanCreateProject;
    use Cleaning;

    /**
     * @var string
     */
    private $path = 'testing/images/product.png';

    /**
     * @var string
     */
    protected $directory = 'app/public/';

    /** @test */
    public function it_uploads_an_url_image(): void
    {
        $response = $this->post('/gallery/url', [
            'url' => $this->url()
        ], $this->authorizationHeaders());

        $image = $response->decodeResponseJson()['data']['images']['data'][0];

        $this->assertDatabaseHas('user_gallery', [
            'id'   => $image['id'],
            'path' => $this->stripDomain($image['path'])
        ]);

        $response->assertJsonStructure([
            'data' => [
                'images' => [
                    'data' => [
                        [
                            'id',
                            'path'
                        ]
                    ]
                ]
            ]
        ]);

        $this->checksForFileExistence($image['path']);

        $this->deleteUserGalleryImageOrDirectory(Image::find($image['id']));
    }

    /** @test */
    public function it_uploads_image_file_to_the_project(): void
    {
        $project = $this->createProject();

        $response = $this->post('/gallery/url', [
            'url'        => $this->url(),
            'project_id' => $project->id
        ], $this->authorizationHeaders());

        $image = $response->decodeResponseJson()['data']['images']['data'][0];
        $component = $response->decodeResponseJson()['data']['components']['data'][0];

        $this->assertDatabaseHas('user_gallery', [
            'id'   => $image['id'],
            'path' => $this->stripDomain($image['path'])
        ]);

        $this->assertDatabaseHas('componentable_images', [
            'path' => $this->stripDomain($component['componentable']['path'])
        ]);

        $response->assertJsonStructure([
            'data' => [
                'images'     => [
                    'data' => [
                        [
                            'id',
                            'path'
                        ]
                    ]
                ],
                'components' => [
                    'data' => [
                        [
                            'id',
                            'visibility',
                            'position_x',
                            'position_y',
                            'height',
                            'width',
                            'order',
                            'componentable_type',
                            'componentable' => [
                                'path'
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->checksForFileExistence($image['path']);
        $this->checksForFileExistence($component['componentable']['path']);

        $this->deleteUserGalleryImageOrDirectory(Image::find($image['id']));
        $this->deleteComponentImageOrDirectory(Component::find($component['id']));
    }

    /**
     * @return string
     */
    private function url(): string
    {
        return str_replace('/api', '', url($this->path));
    }
}
