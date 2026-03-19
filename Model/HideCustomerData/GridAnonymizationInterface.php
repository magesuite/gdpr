<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Model\HideCustomerData;

interface GridAnonymizationInterface
{
    public function canApplyToGrid(string $grid): bool;

    public function canApplyToDataKey(string $dataKey, ?string $grid = null): bool;

    public function getDataKeys(): array;
}
