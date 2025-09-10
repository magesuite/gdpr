<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Model\HideCustomerData;

class AbstractAnonymizedAction
{
    protected array $anonymizedActions = [];

    public function getAnonymizedActions(): array
    {
        return $this->anonymizedActions;
    }
}
