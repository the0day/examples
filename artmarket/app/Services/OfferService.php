<?php

namespace App\Services;

use App\DTO\Offer\OfferData;
use App\Enums\MediaCollectionType;
use App\Models\Glossary\OfferType;
use App\Models\Offer;
use App\Models\OfferOption;
use App\Models\User;
use File;
use Illuminate\Support\Collection;

class OfferService
{
    public static function store(OfferType $offerType, User $user, OfferData $offerData): Offer
    {
        $offer = self::processOffer($offerType, $user, $offerData);
        $offer->options()->saveMany(self::processOptions($offer, $offerData));

        return $offer;
    }

    private static function processOffer(OfferType $offerType, User $user, OfferData $offerData): Offer
    {
        $offer = self::findOrNewOffer($offerData->id, $offerType->id)
            ->fill([
                'title'       => $offerData->title,
                'alias'       => slug($offerData->alias ?? $offerData->title),
                'description' => $offerData->description,
                'price'       => $offerData->price,
                'currency'    => $offerData->currency,
                'active'      => 1
            ]);

        $offer->save();

        $offer->categories()->sync($offerData->category_ids);

        /** @var File $upload */
        foreach ($offerData->uploads as $upload) {
            MediaService::attach($offer, $user, $upload, MediaCollectionType::offer());
        }

        return $offer;
    }

    private static function processOptions(Offer $offer, OfferData $offerData)
    {
        $options = collect();
        $groups = $offer->offerType->optionGroups;
        $availableOptions = self::getMergedOptions($groups);

        foreach ($offerData->options as $optionId => $optionData) {
            if (!$data = $optionData->getModelData()) {
                continue;
            }

            $hasAvailable = $availableOptions->where('id', '=', $optionId)->first();
            if ($optionId > 0 && !$hasAvailable) {
                continue;
            }

            $group = $groups->where('id', '=', $hasAvailable->group_id)->first();
            $option = self::getExistingOptionAndUpdate($offer, $optionId, $data);

            if (!$group->type->isInfo()) {
                $option->name = "";
            }

            if (($group->type->isInfo() && $option->name) || !$group->type->isInfo()) {
                $options->push($option);
            }
        }

        $ids = $options->pluck('option_id');
        $offer->options()->whereNotIn('option_id', $ids)->delete();

        return $options;
    }

    /**
     * @param Collection $groups
     * @return Collection
     */
    private static function getMergedOptions(Collection $groups): Collection
    {
        $availableOptions = collect();
        foreach ($groups as $group) {
            foreach ($group->options as $option) {
                $availableOptions->push($option);
            }
        }

        return $availableOptions;
    }

    /**
     * @param Offer $offer
     * @param int $optionId
     * @param array $data
     * @return OfferOption
     */
    private static function getExistingOptionAndUpdate(Offer $offer, int $optionId, array $data): OfferOption
    {
        /** @var OfferOption $option */
        $option = $offer
            ->options
            ->firstWhere('option_id', '=', $optionId);

        if (is_null($option)) {
            $option = $offer->options()->make($data);
        } else {
            $option->fill($data);
            $option->field_type = $option->glossary->field_type;
        }

        $option->option_id = $optionId == 0 ? null : $optionId;
        $option->currency = currency();

        return $option;
    }

    public static function findOrNewOffer(?int $offerId, int $offerTypeId): Offer
    {
        $offer = Offer::findOrNew($offerId);

        if (!$offer->id) {
            $offer->offer_type_id = $offerTypeId;
            $offer->user_id = auth()->user()->id;
        }

        return $offer;
    }
}
