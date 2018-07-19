<?php declare(strict_types=1);

namespace App\Transformers\Gallery;

use App\Models\Gallery\Image;
use Illuminate\Support\Facades\Storage;
use League\Fractal\TransformerAbstract;

final class ImageTransformer extends TransformerAbstract
{
    /**
     * @param Image $image
     * @return array
     */
    public function transform(Image $image): array
    {
        return [
            'id'   => (int) $image->id,
            'path' => Storage::url($image->path)
        ];
    }
}
