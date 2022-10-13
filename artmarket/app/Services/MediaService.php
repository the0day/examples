<?php

namespace App\Services;

use App\Models\Media;
use App\Models\User;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaService
{
    public static function attach(HasMedia $model, User $user, UploadedFile $file, string $type = null): ?Media
    {
        try {
            return $model->addMedia($file)
                ->preservingOriginal()
                ->toMediaCollection($type ?? null)
                ->setUserId($user->id);
        } catch (FileDoesNotExist|FileIsTooBig $e) {
            return null;
        }
    }
}