<?php

namespace App\DTO;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Intervention\Image\Image;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class ImageMeta extends CastableObjectData implements Castable
{
    public int $width;
    public int $height;

    /**
     * @throws UnknownProperties
     */
    public static function fromImage(Image $image): self
    {
        return new self(
            width: $image->width(),
            height: $image->height(),
        );
    }
}
