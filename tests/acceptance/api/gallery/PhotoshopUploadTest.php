<?php declare(strict_types=1);

namespace Tests\Acceptance\Api\Gallery;

use App\Models\Component;
use App\Models\Gallery\Image;
use App\Models\User;
use Symfony\Component\HttpFoundation\Request;
use Tests\Acceptance\Api\CanCreateProject;
use Tests\Acceptance\Api\CanSendFileRequest;
use Tests\Acceptance\Api\Cleaning;
use Tests\AcceptanceTestCase;
use Tests\Support\UploadedFile;

final class PhotoshopUploadTest extends AcceptanceTestCase
{
    use ChecksForFileExistence;
    use CanCreateProject;
    use CanSendFileRequest;
    use Cleaning;

    /**
     * @var string
     */
    protected $directory = 'app/public/';

    /**
     * @var string
     */
    private $path = 'tests/fixtures/images/psds/1.psd';

    /**
     * @var string[]
     */
    private $componentsStructure = [
        'id',
        'visibility',
        'position_x',
        'position_y',
        'height',
        'width',
        'order',
        'componentable_type',
        'componentable' => [
            'path',
        ],
    ];

    public function setUp(): void
    {
        parent::setUp();

        factory(User::class)->create(['id' => 1]); // TODO temporary until auth implementation
    }

    /** @test */
    public function it_uploads_a_photoshop_file(): void
    {
        $response = $this->file(
            Request::METHOD_POST,
            '/gallery',
            [],
            ['files' => [UploadedFile::makeFrom($this->path)]],
            $this->authorizationHeaders()
        );

        $response->assertJsonStructure([
            'data' => [
                'images' => [
                    'data' => [
                        '*' => [
                            'id',
                            'path',
                        ],
                    ],
                ],
            ],
        ]);

        $json = $response->decodeResponseJson();

        $this->assertDatabaseHas('user_gallery', [
            'id'   => $json['data']['images']['data'][0]['id'],
            'path' => $this->stripDomain($json['data']['images']['data'][0]['path'])
        ]);

        $this->checksForFileExistence(
            $json['data']['images']['data'][0]['path']
        );

        $this->checksForFileExistence(
            $json['data']['images']['data'][1]['path']
        );

        $this->deleteUserGalleryImageOrDirectory(
            Image::find($response->decodeResponseJson()['data']['images']['data'][0]['id'])
        );
    }

    /** @test */
    public function it_uploads_a_photoshop_file_to_the_project(): void
    {
        $project = $this->createProject();

        $response = $this->file(
            Request::METHOD_POST,
            '/gallery',
            ['project_id' => $project->id],
            ['files' => [UploadedFile::makeFrom($this->path)]],
            $this->authorizationHeaders()
        );

        $response->assertJsonStructure([
            'data' => [
                'images'     => [
                    'data' => [
                        '*' => [
                            'id',
                            'path',
                        ],
                    ],
                ],
                'components' => [
                    'data' => [
                        '*' => $this->componentsStructure,
                    ],
                ],
            ],
        ]);

        $json = $response->decodeResponseJson();

        for ($i = 0; $i < 2; $i++) {
            $image = $json['data']['images']['data'][$i];
            $componentPath = $json['data']['components']['data'][$i]['componentable']['path'];

            $this->checksForFileExistence($image['path']);
            $this->checksForFileExistence($componentPath);

            $this->assertDatabaseHas('user_gallery', [
                'id'   => $image['id'],
                'path' => $this->stripDomain($image['path'])
            ]);

            $this->assertDatabaseHas('componentable_images', [
                'path' => $this->stripDomain($componentPath)
            ]);
        }

        $this->assertCount(2, $json['data']['images']['data']);
        $this->assertCount(2, $json['data']['components']['data']);

        $this->deleteUserGalleryImageOrDirectory(
            Image::find($response->decodeResponseJson()['data']['images']['data'][0]['id'])
        );

        $this->deleteComponentImageOrDirectory(
            Component::find($response->decodeResponseJson()['data']['components']['data'][0]['id'])
        );
    }

    /** @test */
    public function it_checks_photoshop_upload_by_project_request(): void
    {
        $project = $this->createProject();

        $response = $this->file(
            Request::METHOD_POST,
            '/gallery',
            ['project_id' => $project->id],
            ['files' => [UploadedFile::makeFrom($this->path)]],
            $this->authorizationHeaders()
        );

        for ($i = 0; $i < 2; $i++) {
            $this->checksForFileExistence(
                $response->decodeResponseJson()['data']['images']['data'][$i]['path']
            );

            $this->checksForFileExistence(
                $response->decodeResponseJson()['data']['components']['data'][$i]['componentable']['path']
            );
        }

        $response = $this->get('project/' . $project->id, $this->authorizationHeaders());

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'type'       => [
                    'data' => [
                        'name',
                        'width',
                        'height',
                    ],
                ],
                'components' => [
                    'data' => [
                        '*' => $this->componentsStructure,
                    ],
                ],
            ],
        ]);

        $this->deleteComponentImageOrDirectory(
            $component = Component::find($response->decodeResponseJson()['data']['components']['data'][0]['id'])
        );

        $this->deleteUserGalleryImageOrDirectory(
            $component->project->user->gallery->first()
        );
    }
}
