<?php declare(strict_types=1);

namespace App\Components\Factories;

use App\Exceptions\UnknownComponentableTypeException;
use App\Http\Requests\ComponentRequest;
use App\Models\Componentables\Componentable;
use App\Models\Componentables\Image;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

final class ImageFactory extends BaseFactory
{

    use HasArrayName;

    /**
     * @var string
     */
    protected $directory = 'components';

    /**
     * @param ComponentRequest $request
     * @return \Illuminate\Validation\Validator
     */
    public function validator(ComponentRequest $request): \Illuminate\Validation\Validator
    {
        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($request->all(), [
            'componentable.image_id' => 'required',
        ]);

        $image = \App\Models\Gallery\Image::findOrFail(
            $request->input('componentable.image_id')
        );

        $validator->after(function (\Illuminate\Validation\Validator $validator) use ($image, $request) {
            if ($image->user_id !== $request->user()->id) {
                $validator->errors()->add('image_id', 'The image does not belong to the user.');
            }
        });

        return $validator;
    }

    /**
     * @param array $values
     * @return Componentable
     * @throws UnknownComponentableTypeException
     */
    public function create(array $values): Componentable
    {
        $image = $this->image($values);

        return Image::create($this->applyDefaultValues(Componentable::IMAGE, [
            'path'   => $this->copy($image),
            'width'  => $image->width,
            'height' => $image->height,
        ]));
    }

    /**
     * @param array $values
     * @return \App\Models\Gallery\Image
     */
    protected function image(array $values): \App\Models\Gallery\Image
    {
        return \App\Models\Gallery\Image::findOrFail($values['image_id']);
    }

    /**
     * @param \App\Models\Gallery\Image $image
     * @return string
     */
    protected function copy(\App\Models\Gallery\Image $image): string
    {
        $path = $this->changeImageName($image);

        $this->storage->copy($image->path, $path);

        return $path;
    }

    /**
     * @param \App\Models\Gallery\Image $image
     * @return string
     */
    protected function changeImageName(\App\Models\Gallery\Image $image): string
    {
        return sprintf('%s/%s.%s',
            $this->directory($image),
            $this->generateName($image->path),
            $this->extension($image->path)
        );
    }

    /**
     * @param string $path
     * @return string
     */
    protected function generateName(string $path): string
    {
        return preg_replace('/(.+)_(.+)\.\w{1,4}$/', time() . random_int(10, 99) . '_$2', basename($path));
    }

    /**
     * @param string $path
     * @return string
     */
    protected function extension(string $path): string
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    /**
     * @param \App\Models\Gallery\Image $image
     * @return string
     */
    protected function directory(\App\Models\Gallery\Image $image): string
    {
        return Str::replaceFirst('gallery', 'components', pathinfo($image->path, PATHINFO_DIRNAME));
    }
}
