<?php

namespace Tests\Unit;

use App\Enums\MediaCollectionType;
use App\Models\Media;
use App\Models\Order;
use Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class OrderTest extends TestCase
{
    public function testUpgradesIsNullDTO()
    {
        $order = Order::factory()->create();
        $this->assertTrue($order->save(), 'Can not save the order');
        $this->assertCount(0, $order->upgrades, 'There is not null upgrades');
    }

    public function testUploadSamplesInOrder()
    {
        $this->actingAs($user = $this->createUser());

        $order = Order::factory()->create();
        $files = [
            UploadedFile::fake()->image('avatar-1.jpg', '1920', '1080'),
            UploadedFile::fake()->image('avatar-2.jpg', '1920', '1080')
        ];
        $this->orderService->attachSamples($order, $user, $files);

        $this->assertCount(count($files), $order->getSampleMedia(), 'Invalid samples for order');

        $firstMedia = $order->getFirstMedia(MediaCollectionType::orderSample());

        $this->assertInstanceOf(Media::class, $firstMedia, 'Uploaded image is not Media class');
        $this->orderService->deAttachSample($order, $firstMedia);
        $order->refresh();
        $this->assertCount(count($files) - 1, $order->getSampleMedia(), 'Invalid samples for order after deleting');
    }

    public function testHasAccessFunctions()
    {
        $order = $this->createOrderDirectly();

        $this->assertFalse(Gate::check('view', $order), '3rd user has access to order');
        $this->assertFalse(Gate::forUser($this->createUser())->check('view', $order), '3rd user has access to order');

        $gateSeller = Gate::forUser($order->seller);
        $gateBuyer = Gate::forUser($order->buyer);

        $this->assertFalse($gateSeller->check('buyer', $order), 'seller has been detected as buyer');
        $this->assertTrue($gateSeller->check('seller', $order), 'seller is not detected');
        $this->assertFalse(Gate::check('view', $order), 'Guest has access');
        $this->assertTrue($gateSeller->check('view', $order), 'seller has not access');

        $this->assertFalse($gateBuyer->check('seller', $order), 'buyer has been detected as seller');
        $this->assertTrue($gateBuyer->check('buyer', $order), 'buyer is not detected');
        $this->assertTrue($gateBuyer->check('view', $order), 'buyer has not access');
    }

    public function testUploadWorkBySeller()
    {
        $order = $this->createOrderDirectly();

        $sketchFile = UploadedFile::fake()->image('sketch-1.jpg', '1920', '1080');
        $finalFile = UploadedFile::fake()->image('final-1.jpg', '1920', '1080');
        $sketch = $this->orderService->attachSketch($order, $order->seller, $sketchFile);
        $final = $this->orderService->attachFinal($order, $order->seller, $finalFile);
        $this->assertInstanceOf(Media::class, $sketch, 'Attached sketch image is not Media class');
        $this->assertInstanceOf(Media::class, $final, 'Attached final image is not Media class');

        $this->assertEquals($sketch->toArray(), $order->getSketchMedia()[0]->toArray(), 'uploaded sketch is invalid');
        $this->assertEquals($final->toArray(), $order->getFinalMedia()[0]->toArray(), 'uploaded final is invalid');

        $this->expectException(AuthorizationException::class);
        $this->orderService->attachSketch($order, $order->buyer, $sketchFile);
        $this->orderService->attachFinal($order, $order->buyer, $finalFile);
    }
}
