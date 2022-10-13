<?php

namespace GameData\Game;

use Bundles\ABTest\ABTestComponent;
use Bundles\ABTest\Entity\ABTestEntity;
use Bundles\ABTest\Entity\ABTestGroupEntity;
use Bundles\ABTest\Manager\ABTestGroupManager;
use Bundles\ABTest\Manager\ABTestManager;
use Bundles\ABTest\Setting\ABTestSettings;
use Bundles\Account\Entity\UserEntity;
use GameData\WarmupableGameData;

class ABTest implements WarmupableGameData
{
    /** @var ABTestEntity[] $ABTests */
    protected array $ABTests = [];
    /** @var ABTestGroupEntity[][] $userGroups */
    private array $userGroups;

    public function update(): void
    {
        $this->ABTests = $this->warmUp();
    }

    public function getABTests(): array
    {
        if (app()->isTest()) {
            return $this->getABTestManager()->getAll();
        }
        return $this->ABTests ?? [];
    }

    public function getABTestById(int $id): ?ABTestEntity
    {
        return $this->getABTests()[$id] ?? null;
    }

    public function getABTestByKey(string $key, ?int $entityId = null): ?ABTestEntity
    {
        $list = array_filter($this->getABTests(), function (ABTestEntity $item) use ($key, $entityId) {
            return $key == $item->getKey() && (!$entityId || $entityId == $item->getEntityId());
        });

        return current($list) ?: null;
    }

    /**
     * @param string $key
     * @param int|null $entityId
     * @return ABTestEntity[]
     */
    public function getAllABTestByKey(string $key, int $entityId = null): array
    {
        return array_filter($this->getABTests(), function (ABTestEntity $item) use ($key, $entityId) {
            return $key == $item->getKey() && (!$entityId || $entityId == $item->getEntityId());
        });
    }

    public function getUserGroup(ABTestEntity $test, ABTestSettings $settings, UserEntity $user)
    {
        if (isset($this->userGroups[$test->getId()][$user->getId()])) {
            return $this->userGroups[$test->getId()][$user->getId()];
        }

        $group = $this->getABTestGroupManager()->cachedUserGroup($test, $settings, $user);

        return $this->userGroups[$test->getId()][$user->getId()] = $group;
    }

    public function warmUp(): array
    {
        return $this->component()->getABTestManager()->getCachedAll();
    }

    public function import(array $data): void
    {
        $this->ABTests = $data;
    }

    public function component(): ABTestComponent
    {
        return locator()->getABTestComponent();
    }

    public function getABTestManager(): ABTestManager
    {
        return $this->component()->getABTestManager();
    }

    public function getABTestGroupManager(): ABTestGroupManager
    {
        return $this->component()->getABTestGroupManager();
    }
}