<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Model\HideCustomerData;

abstract class AbstractGridAnonymization implements GridAnonymizationInterface
{
    protected array $grids = [];
    protected array $dataKeys = [];

    public function canApplyToGrid(string $grid): bool
    {
        return in_array($grid, $this->grids);
    }

    public function canApplyToDataKey(string $dataKey, ?string $grid = null): bool
    {
        if (!empty($grid)) {
            return in_array($dataKey, $this->dataKeys) && in_array($grid, $this->grids);
        }

        return in_array($dataKey, $this->dataKeys);
    }

    public function getDataKeys(): array
    {
        return $this->dataKeys;
    }
}
