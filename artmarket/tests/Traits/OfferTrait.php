<?php

namespace Tests\Traits;

use App\Enums\OptionFieldType;
use App\Models\Glossary\Option;
use App\Models\Offer;
use App\Models\OfferOption;
use Cknow\Money\Money;
use Faker\Factory;

trait OfferTrait
{
    /**
     * @return Offer
     */
    protected function createOfferWithOptions(): Offer
    {
        $offer = Offer::factory(['price' => Money::USD(1000)])->create();

        $offer->options()->saveMany([
            $this->getSoftwareOption($offer->id),
            $this->getColorOption($offer->id),
            $this->getCopyrightOption($offer->id),
            $this->getSubjectsOption($offer->id)
        ]);

        return $offer;
    }


    /**
     * @param Offer $offer
     * @return array
     */
    protected function getOptionsData(Offer $offer): array
    {
        $options = [];
        foreach ($offer->options as $option) {
            $options[$option->id] = $this->getRandomValueByField($option->glossary->field_type, $option->field_values);
        }
        $this->assertGreaterThan(0, count($options));
        return $options;
    }

    protected function getSoftwareOption(int $offerId): ?OfferOption
    {
        $softwareOption = Option::where('alias', '=', 'software')->first();
        if ($softwareOption) {
            return OfferOption::factory()->create([
                'offer_id'   => $offerId,
                'option_id'  => $softwareOption->id,
                'field_type' => $softwareOption->field_type,
                'name'       => 'Test option value'
            ]);
        }

        return null;
    }

    /**
     * @param int $offerId
     * @return ?OfferOption
     */
    protected function getColorOption(int $offerId): ?OfferOption
    {
        $colorOption = Option::where('alias', '=', 'color')->first();
        if ($colorOption) {
            return OfferOption::factory()->create([
                'name'         => '',
                'offer_id'     => $offerId,
                'option_id'    => $colorOption->id,
                'field_type'   => $colorOption->field_type,
                'field_values' => [
                    'colored'    => [
                        'days'  => 2,
                        'price' => 1000
                    ],
                    'monochrome' => [
                        'days'  => 1,
                        'price' => 500
                    ]
                ]
            ]);
        }
        return null;
    }

    /**
     * @param int $offerId
     * @return ?OfferOption
     */
    protected function getCopyrightOption(int $offerId): ?OfferOption
    {
        $copyrightOption = Option::where('alias', '=', 'copyright')->first();
        if ($copyrightOption) {
            return OfferOption::factory()->create([
                'option_id'  => $copyrightOption->id,
                'offer_id'   => $offerId,
                'field_type' => $copyrightOption->field_type,
                'days'       => 1,
                'price'      => 3000
            ]);
        }
        return null;
    }

    /**
     * @param int $offerId
     * @return ?OfferOption
     */
    protected function getSubjectsOption(int $offerId): ?OfferOption
    {
        $subjectOption = Option::where('alias', '=', 'subjects')->first();
        if ($subjectOption) {
            return OfferOption::factory()->create([
                'option_id'  => $subjectOption->id,
                'offer_id'   => $offerId,
                'field_type' => $subjectOption->field_type,
                'days'       => 0,
                'price'      => 10000
            ]);
        }
        return null;
    }

    protected function getRandomValueByField(OptionFieldType $type, ?array $values = []): mixed
    {
        switch ($type) {
            case OptionFieldType::checkbox():
                return mt_rand(0, 1);

            case OptionFieldType::number():
                return mt_rand(0, 10);

            case OptionFieldType::text():
                return Factory::create()->paragraph;

            case OptionFieldType::radio():
            case OptionFieldType::select():
                if (!is_array($values) || count($values) == 0) {
                    return '';
                }
                $keys = array_keys($values);
                $rand = array_rand($keys);
                return $keys[$rand];
        }

        return '';
    }
}