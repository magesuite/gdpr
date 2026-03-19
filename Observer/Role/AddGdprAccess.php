<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Observer\Role;

class AddGdprAccess implements \Magento\Framework\Event\ObserverInterface
{
    public function __construct(
        protected \MageSuite\Gdpr\Model\Authorization\CanChangeGdprAccess $canChangeGdprAccess,
        protected \Magento\Framework\App\RequestInterface $request,
    ) {}

    public function execute(\Magento\Framework\Event\Observer $observer): void
    {
        if (!$this->canChangeGdprAccess->execute()) {
            return;
        }

        $newAccess = $this->request->getParam(\MageSuite\Gdpr\Block\Role\Tab\Info::GDPR_FIELD);

        $role = $observer->getEvent()->getData('object');
        $role->setData(\MageSuite\Gdpr\Block\Role\Tab\Info::GDPR_FIELD, $newAccess);
    }
}
