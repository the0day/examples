<?php

namespace Tests\Feature;

use App\Models\Glossary\OfferType;
use App\Models\Glossary\Option;
use App\Models\Offer;
use Cknow\Money\Money;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class MyOffersTest extends TestCase
{
    private $user;
    private $offerType;

    /**
     * @dataProvider provider
     */
    public function testValidation(array $postData, array $validations)
    {
        $this->actingAs($this->getUser());

        foreach ($validations as $key => $validation) {
            if (isset($validation[2]['attribute'])) {
                $validation[2]['attribute'] = __($validation[2]['attribute']);
            }
            $validations[$validation[0]] = __($validation[1], $validation[2]);
            unset($validations[$key]);
        }

        $this->post(route('account.offers.store'), $postData)
            ->assertInvalid($validations);
    }

    public function testNotFoundOffer()
    {
        $this->actingAs($user = $this->getUser());
        $this->get(route('account.offers.edit', 999999))->assertStatus(404);
        $this->get(route('offer.view', [$user->name, 'qqqqqqqq']))->assertStatus(404);
    }

    /**
     * @test
     */
    public function testCanAddNewOffer()
    {
        $this->actingAs($user = $this->getUser());

        $data = $this->getValidData();

        $this->get(route('account.offers.create', $this->getOfferType()->alias))
            ->assertStatus(200);

        $this
            ->post(route('account.offers.store'), $data)
            ->assertSessionHas('success', __('app.saved'));

        $offer = Offer::where('alias', '=', $data['alias'])->first();

        $actualCategories = $offer->categories->pluck('id')->toArray();
        sort($actualCategories);
        $this->assertNotNull($offer);
        $this->assertEquals($data['title'], $offer->title);
        $this->assertEquals($data['alias'], $offer->alias);
        $this->assertTrue($offer->price->equals(Money::USD($data['price'] * 100)));
        $this->assertEquals($data['category_ids'], $actualCategories);

        $media = $offer->getPreviewMedia();

        /** @var File $file */
        foreach ($data['image'] as $file) {
            $this->assertTrue($media->contains('size', '=', $file->getSize()), 'Invalid filesize');
        }

        foreach ($offer->options()->with('glossary')->get() as $option) {
            $sentOption = $data['options'][$option->option_id];
            $sentFields = $sentOption['fields'];

            if ($option->glossary->hasFieldValues()) {
                $existedFields = $option->field_values;

                foreach ($option->glossary->field_values as $subOptionKey => $subOptionTitle) {
                    $this->assertEquals($sentFields[$subOptionKey]['days'], $existedFields[$subOptionKey]['days']);
                    $this->assertEquals($sentFields[$subOptionKey]['price'] * 100, $existedFields[$subOptionKey]['price']);

                    $optionPrice = $option->getPrice($subOptionKey);
                    $sentPrice = Money::parseByDecimal($sentFields[$subOptionKey]['price'], 'USD');

                    $this->assertTrue($optionPrice->equals($sentPrice));
                }
            } else {
                $this->assertEquals($sentFields['0']['days'], $option->days);
                $this->assertEquals($sentFields['0']['price'], $option->price->formatByDecimal());
                $this->assertTrue($option->price->equals(Money::USD($sentFields['0']['price'] * 100)));
            }
        }

        $this->get(route('offer.view', [$user->name, $offer->alias]))
            ->assertStatus(200);

        $this->get(route('account.offers.edit', $offer->id))
            ->assertStatus(200);
    }

    private function getUser()
    {
        if (!$this->user) {
            $this->user = $this->createUser();
        }

        return $this->user;
    }

    private function getOfferType()
    {
        if (!$this->offerType) {
            $this->offerType = OfferType::whereAlias('art')->first();
        }

        return $this->offerType;
    }

    public function provider()
    {
        return [
            'required data'    => $this->getRequiredValidationData(),
            'min length data'  => $this->getMinValidationData(),
            'max length daata' => $this->getMaxValidationData(),
        ];
    }

    /**
     * @return array
     */
    private function getRequiredValidationData(): array
    {
        return [
            [
                'title'        => [],
                'alias'        => [],
                'description'  => [],
                'options'      => 'string',
                'category_ids' => '0',
            ],
            [
                ['title', 'validation.string', ['attribute' => 'validation.attributes.title']],
                ['alias', 'validation.string', ['attribute' => 'validation.attributes.alias']],
                ['description', 'validation.string', ['attribute' => 'validation.attributes.description']],
                ['options', 'validation.array', ['attribute' => 'validation.attributes.options']],
                ['category_ids', 'validation.array', ['attribute' => 'validation.attributes.category_ids']],
                ['price', 'validation.required', ['attribute' => 'validation.attributes.price']],
                ['offer_type_id', 'validation.required', ['attribute' => 'validation.attributes.offer_type_id']],
                ['image', 'validation.required_without', ['attribute' => 'validation.attributes.image', 'values' => 'id']]
            ]
        ];
    }

    /**
     * @return array
     */
    private function getMinValidationData(): array
    {
        return [
            [
                'title'         => 's',
                'alias'         => 'alias',
                'price'         => 1,
                'category_ids'  => [2222],
                'offer_type_id' => 2222,
                'image'         => [
                    '0' => UploadedFile::fake()->image('invalidfile.txt', 100, 200),
                    '1' => UploadedFile::fake()->image('smallfile.jpg', 512, 512),
                ],
                'options'       => [
                    '1' => [
                        'name' => 'null',
                    ]
                ]
            ],
            [
                ['title', 'validation.min.string', ['attribute' => 'validation.attributes.title', 'min' => 10]],
                ['category_ids.0', 'validation.exists', ['attribute' => 'validation.attributes.category_ids']],
                ['offer_type_id', 'validation.exists', ['attribute' => 'validation.attributes.offer_type_id']],
                ['image.0', 'validation.mimes', ['attribute' => 'validation.attributes.image', 'values' => 'png, jpg, jpeg']],
                //['image.1', 'validation.min.file', ['attribute' => 'Image', 'min' => 32]],

            ]
        ];
    }

    /**
     * @return array
     */
    private function getMaxValidationData(): array
    {
        return [
            [
                'title'       => str_repeat("w", 81),
                'description' => str_repeat("w", 1201),
                'image'       => [
                    '2' => UploadedFile::fake()->create('bigfile', 4097),
                ]
            ],
            [
                ['title', 'validation.max.string', ['attribute' => 'validation.attributes.title', 'max' => 80]],
                ['image.2', 'validation.max.file', ['attribute' => 'validation.attributes.image', 'max' => 4096]],
                ['description', 'validation.max.string', ['attribute' => 'validation.attributes.description', 'max' => 1200]]
            ],
        ];
    }

    private function getValidData()
    {
        $offerType = $this->getOfferType();

        /** @var Option $colored */
        $colored = Option::where('alias', '=', 'color')->first();
        $colorData = [];
        foreach ($colored->field_values as $field => $title) {
            $colorData[$field] = [
                'days'  => mt_rand(1, 3),
                'price' => mt_rand(2, 10)
            ];
        }

        /** @var Option $copyright */
        $copyright = Option::where('alias', '=', 'copyright')->first();
        $copyrightData[0] = [
            'days'  => mt_rand(1, 3),
            'price' => mt_rand(2, 10)
        ];

        /** @var Option $composition */
        $composition = Option::where('alias', '=', 'composition')->first();
        $compositionData = [];
        foreach ($composition->field_values as $field => $title) {
            $compositionData[$field] = [
                'days'  => mt_rand(1, 3),
                'price' => mt_rand(2, 10)
            ];
        }

        /** @var Option $background */
        $background = Option::where('alias', '=', 'background')->first();
        $backgroundData = [];
        foreach ($background->field_values as $field => $title) {
            $backgroundData[$field] = [
                'days'  => mt_rand(1, 3),
                'price' => mt_rand(2, 10)
            ];
        }

        $this
            ->get(route("account.offers.create", $offerType->alias))
            ->assertStatus(200);

        $categoryIds = [$offerType->categories()->first()->id];
        sort($categoryIds);

        $data = [
            'title'         => $title = uniqid(),
            'alias'         => $title,
            'offer_type_id' => $offerType->id,
            'category_ids'  => $categoryIds,
            'price'         => 100,
            'currency'      => 'USD',
            'image'         => [
                0 => UploadedFile::fake()->image('avatar.jpg', $w = '1920', $h = '1080'),
                1 => UploadedFile::fake()->image('avatar-2.jpg', $w2 = '1920', $h2 = '1920'),
            ],
            'options'       => [
                $colored->id     => [
                    'name'   => $colored->alias,
                    'fields' => $colorData
                ],
                $copyright->id   => [
                    'name'   => $copyright->alias,
                    'fields' => $copyrightData
                ],
                $composition->id => [
                    'name'   => $composition->alias,
                    'fields' => $compositionData
                ],
                $background->id  => [
                    'fields' => $backgroundData
                ]
            ],
        ];

        return $data;
    }
}
