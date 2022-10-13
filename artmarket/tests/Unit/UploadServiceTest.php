<?php

namespace Tests\Unit;

use App\DTO\ImageMeta;
use App\Models\Upload;
use App\Services\UploadService;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UploadServiceTest extends TestCase
{
    /**
     * @test
     */
    public function testCanUpload()
    {
        $user = $this->createUser();
        $file = UploadedFile::fake()->image('avatar.jpg', $w = '123', $h = '66');

        $response = UploadService::upload($user, $file);
        $this->assertNotNull($response);

        $upload = Upload::find($response->id);
        $this->assertInstanceOf(ImageMeta::class, $upload->meta);
        $this->assertEquals($w, $upload->meta->width);
        $this->assertEquals($h, $upload->meta->height);
    }
}
