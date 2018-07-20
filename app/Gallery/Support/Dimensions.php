<?php declare(strict_types=1);

namespace App\Gallery\Support;

use Intervention\Image\Constraint;
use Intervention\Image\Image;

final class Dimensions extends \App\Support\Dimensions
{
    /**
     * @param Image $image
     * @param int $width
     * @param int $height
     * @return Image
     */
    public static function resize(Image $image, int $width = 2000, int $height = 2000): Image
    {
        if ($image->width() > $width || $image->height() > $height) {
            $image->resize($width, $height, function (Constraint $constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        return $image;
    }
}
