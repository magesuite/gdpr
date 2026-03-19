<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Model\Authorization;

class CanAccessCustomerData
{
    public function __construct(
        protected \Magento\Backend\Model\Auth\Session $backendSession,
    ) {}

    public function execute(): bool
    {
        return (bool)$this->backendSession->getUser()->getRole()->getData(\MageSuite\Gdpr\Block\Role\Tab\Info::GDPR_FIELD);
    }
}
