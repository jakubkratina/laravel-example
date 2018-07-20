<?php declare(strict_types=1);

namespace Tests\Acceptance\Api\Gallery;

use App\Exceptions\UnsupportedFileExtensionException;
use Tests\AcceptanceTestCase;

final class UnsupportedUrlExtensionTest extends AcceptanceTestCase
{
    /**
     * @var string
     */
    private $path = 'testing/images/product.pdf';

    /** @test */
    public function it_fails_on_required_url(): void
    {
        $response = $this->post('/gallery/url', [], $this->authorizationHeaders());

        $response->assertJson([
            'errors' => [
                'url' => [
                    'validation.required'
                ]
            ]
        ]);
    }

    /** @test */
    public function it_send_unsupported_url_file_extension(): void
    {
        $response = $this->post('/gallery/url', [
            'url' => $this->url()
        ], $this->authorizationHeaders());

        $response->assertJson([
            'errors' => [
                'url' => [
                    (new UnsupportedFileExtensionException('pdf'))->getMessage()
                ]
            ]
        ]);
    }

    /**
     * @return string
     */
    private function url(): string
    {
        return str_replace('/api', '', url($this->path));
    }
}
