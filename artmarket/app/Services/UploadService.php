<?php

namespace App\Services;

use App\DTO\ImageMeta;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Image;
use Log;
use Storage;

class UploadService
{
    public static function upload(User $user, UploadedFile $file): ?Upload
    {
        $filename = uniqid() . "." . $file->extension();
        $path = 'uploads';

        $uploaded = self::getDisk()->putFileAs($path, $file, $filename);

        if (!self::getDisk()->exists($uploaded)) {
            Log::error('The file has not been uploaded');

            return null;
        }

        $meta = self::getImageMeta($file->getRealPath());

        $created = $user->uploads()->create([
            'name'     => $filename,
            'path'     => $path,
            'mimetype' => $file->getMimeType(),
            'filesize' => $file->getSize(),
            'meta'     => $meta->toArray()
        ]);

        Log::debug('The file uploaded & new record ' . ($created ? "created" : "DOES NOT created"));

        return $created;
    }

    public static function getImageMeta(string $path): ?ImageMeta
    {
        if (!$image = Image::make($path)) {
            return null;
        }

        return ImageMeta::fromImage($image);
    }

    public static function getDisk(): FilesystemAdapter
    {
        return Storage::disk('local');
    }
}
