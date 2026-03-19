<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Model\HideCustomerData;

class AbstractAnonymizedAction
{
    public function __construct(
        /** @var string[] */
        protected array $anonymizedActions = [],
    ) {}

    public function getAnonymizedActions(): array
    {
        return $this->anonymizedActions;
    }
}
