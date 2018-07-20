<?php declare(strict_types=1);

namespace App\Contracts\Gallery;

interface File
{
    /**
     * @return string
     */
    public function path(): string;

    /**
     * @return string
     */
    public function extension(): string;

    /**
     * @return string
     */
    public function hashName(): string;

    /**
     * @return string
     */
    public function source(): string;
}
