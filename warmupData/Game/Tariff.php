<?php

namespace GameData\Game;

use Bundles\Garage\Entity\TariffEntity;
use Bundles\Garage\GarageComponent;
use Bundles\Garage\Manager\TariffManager;
use GameData\WarmupableGameData;

class Tariff implements WarmupableGameData
{
    /** @var TariffEntity[] $items */
    private array $tariffs;

    public function update(): void
    {
        $this->tariffs = $this->warmUp();
    }

    public function getTariffs(): array
    {
        if (app()->isTest()) {
            return $this->getTariffManager()->getAll();
        }
        return $this->tariffs ?? [];
    }

    public function getTariffById(int $id): ?TariffEntity
    {
        return $this->getTariffs()[$id] ?: null;
    }

    public function getItemByKey(string $key): ?TariffEntity
    {
        $list = array_filter($this->getTariffs(), function (TariffEntity $item) use ($key) {
            return $key == $item->getKey();
        });

        return current($list) ?: null;
    }

    public function import(array $data): void
    {
        $this->tariffs = $data;
    }

    public function warmUp(): array
    {
        return $this->getTariffManager()->getCachedList([], 'garage.tariff_list.all');
    }

    private function getTariffManager(): TariffManager
    {
        return $this->component()->getTariffManager();
    }

    private function component(): GarageComponent
    {
        return locator()->getGarageComponent();
    }
}