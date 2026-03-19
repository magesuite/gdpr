<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Plugin\Magento\Payment\Block\Info;

class HideCustomerData extends \MageSuite\Gdpr\Model\HideCustomerData\AbstractHider
{
    public function aroundToPdf(\Magento\Payment\Block\Info $subject, callable $proceed)
    {
        if (!$this->customerDataVisibilityHelper->canSeeCustomerData() and $this->customerDataVisibilityHelper->shouldDataBeAnonymized()) {
            return __('Payment details are not shown. It may contain personal data.');
        }

        return $proceed();
    }
}
