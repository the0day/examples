<?php

namespace App\DTO\Offer;

use App\DTO\Casters\MoneyCaster;
use App\DTO\ObjectData;
use Cknow\Money\Money;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class OfferData extends ObjectData
{
    public ?int $id = null;
    public string $title;
    public ?string $alias;
    public ?string $description;
    #[CastWith(MoneyCaster::class)]
    public Money $price;
    public string $currency = 'USD';
    public array $category_ids;

    /** @var OptionsData[] $options */
    #[CastWith(OptionsCaster::class)]
    public array $options;

    /** @var UploadedFile[] $uploads */
    public array $uploads;

    /**
     * @throws UnknownProperties
     */
    public static function fromRequest(Request $request): OfferData
    {
        return new OfferData(
            id: $request->get('id'),
            title: $request->get('title'),
            alias: $request->get('alias'),
            description: $request->get('description'),
            price: money_parse_by_decimal($request->get('price'), mb_strtoupper($request->get('currency', 'USD'))),
            currency: $request->get('currency', 'USD'),
            options: $request->get('options'),
            category_ids: $request->get('category_ids', []),
            uploads: $request->file('image') ?? []
        );
    }
}
