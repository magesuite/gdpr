<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Model\Authorization;

class CanChangeGdprAccess
{
    public const GDPR_CONFIG_ACL_ROLE = 'MageSuite_Gdpr::gdpr_config';

    public function __construct(
        protected \Magento\Framework\AuthorizationInterface $authorization,
    ) {}

    public function execute(): bool
    {
        return $this->authorization->isAllowed(self::GDPR_CONFIG_ACL_ROLE);
    }
}
