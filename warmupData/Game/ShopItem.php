<?php

namespace GameData\Game;

use Bundles\Account\Entity\UserEntity;
use Bundles\Shop\Entity\ShopItemEntity;
use Bundles\Shop\Entity\ShopUserItemEntity;
use Bundles\Shop\Manager\ShopItemManager;
use Bundles\Shop\Manager\ShopUserItemManager;
use Bundles\Shop\ShopComponent;
use GameData\WarmupableGameData;

class ShopItem implements WarmupableGameData
{
    /** @var ShopItemEntity[] $items */
    private array $items;
    private array $userItems;

    public function update(): void
    {
        $this->items = $this->warmUp();
    }

    public function getItems(): array
    {
        if (app()->isTest()) {
            return $this->getShopItemManager()->getAll();
        }

        return $this->items ?? [];
    }

    public function getItemById(int $id): ?ShopItemEntity
    {
        $list = array_filter($this->getItems(), function (ShopItemEntity $item) use ($id) {
            return $id == $item->getId();
        });

        return current($list) ?: null;
    }

    public function getItemByKey(string $key): ?ShopItemEntity
    {
        $list = array_filter($this->getItems(), function (ShopItemEntity $item) use ($key) {
            return $key == $item->getKey();
        });

        return current($list) ?: null;
    }

    /**
     * @param UserEntity $user
     * @return ShopUserItemEntity[]
     */
    public function loadUserItems(UserEntity $user): array
    {
        if (isset($this->userItems[$user->getId()])) {
            return $this->userItems[$user->getId()];
        }

        $userItems = $this->getShopUserItemManager()->getByUser($user->getId());

        return $this->userItems[$user->getId()] = $userItems;
    }

    public function warmUp(): array
    {
        return $this->getShopItemManager()->getCachedAll();
    }

    public function import(array $data): void
    {
        $this->items = $data;
    }

    private function getShopItemManager(): ShopItemManager
    {
        return $this->component()->getShopItemManager();
    }

    private function getShopUserItemManager(): ShopUserItemManager
    {
        return $this->component()->getShopUserItemManager();
    }

    private function component(): ShopComponent
    {
        return locator()->getShopComponent();
    }
}