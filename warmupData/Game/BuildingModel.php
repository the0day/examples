<?php

namespace GameData\Game;

use Bundles\TaxiPolis\Entity\BuildingModelEntity;
use Bundles\TaxiPolis\Service\BuildingService;
use Bundles\TaxiPolis\TaxiPolisComponent;
use GameData\WarmupableGameData;

class BuildingModel implements WarmupableGameData
{
    /** @var BuildingModelEntity[] $ */
    private array $models;

    public function update(): void
    {
        $this->models = $this->warmUp();
    }

    public function getBuildingModels(): array
    {
        if (app()->isTest()) {
            return $this->component()->getBuildingModelManager()->getAll();
        }
        return $this->models ?? [];
    }

    public function getBuildingModelById(int $id): ?BuildingModelEntity
    {
        $list = array_filter($this->getBuildingModels(), function (BuildingModelEntity $item) use ($id) {
            return $id == $item->getId();
        });

        return current($list) ?: null;
    }

    public function import(array $data): void
    {
        $this->models = $data;
    }

    public function warmUp(): array
    {
        return $this->getBuildingService()->getCachedModels();
    }

    private function getBuildingService(): BuildingService
    {
        return $this->component()->getBuildingService();
    }

    private function component(): TaxiPolisComponent
    {
        return locator()->getTaxiPolisComponent();
    }
}