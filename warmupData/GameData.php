<?php

namespace GameData;

use AppLocator;
use GameData\Game\ABTest;
use GameData\Game\BuildingModel;
use GameData\Game\ShopItem;
use GameData\Game\Tariff;

class GameData
{
    use AppLocator;

    /** @var bool $isTest */
    protected bool $isTest;
    private array $gameData;
    /** @var WarmupableGameData[] */
    private array $classes;

    public function __construct()
    {
        $this->classes = [
            ABTest::class        => new ABTest(),
            ShopItem::class      => new ShopItem(),
            BuildingModel::class => new BuildingModel(),
            Tariff::class        => new Tariff(),
        ];

        $this->isTest = app()->isTest();
        $this->warmUp();
    }

    public function abtest(): ABTest
    {
        return $this->get(ABTest::class);
    }

    public function shopItem(): ShopItem
    {
        return $this->get(ShopItem::class);
    }

    public function tariff(): Tariff
    {
        return $this->get(Tariff::class);
    }

    public function buildingModel(): BuildingModel
    {
        return $this->get(BuildingModel::class);
    }

    /**
     * Подгружаем данные и импортируем по классам
     * @return void
     */
    public function warmUp()
    {
        $this->gameData = $this->loadAll();
        $this->importAll();
    }

    /**
     * Загрузить или получить из кеша огромный блок данных
     * @return array
     */
    private function loadAll(): array
    {
        $cache = $this->locator()->getCacheMulti();

        return $cache->get(self::cachekey(), function () {
            $data = [];
            foreach ($this->getClasses() as $className => $class) {
                $data[$className] = $class->warmUp();
            }

            return $data;
        }, 60 * 10);
    }

    /**
     * Распределить огромный блок данных по классам
     * @return void
     */
    private function importAll(): void
    {
        foreach ($this->getClasses() as $className => $class) {
            $class->import($this->gameData[$className]);
        }
    }

    private function get(string $className): WarmupableGameData
    {
        return $this->classes[$className];
    }

    private function getClasses(): array
    {
        return $this->classes;
    }

    public static function cachekey(): string
    {
        return 'gamedata';
    }
}