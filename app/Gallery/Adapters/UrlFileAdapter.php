<?php declare(strict_types=1);

namespace App\Gallery\Adapters;

use App\Contracts\Gallery\File;

final class UrlFileAdapter implements File
{
    /**
     * @var string
     */
    private $url;

    /**
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function path(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function extension(): string
    {
        return pathinfo($this->url, PATHINFO_EXTENSION);
    }

    /**
     * @return string
     */
    public function hashName(): string
    {
        return time() . random_int(10, 99) . '_' . $this->name();
    }

    /**
     * @return string
     */
    public function source(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    private function name(): string
    {
        return pathinfo($this->url, PATHINFO_BASENAME);
    }
}
