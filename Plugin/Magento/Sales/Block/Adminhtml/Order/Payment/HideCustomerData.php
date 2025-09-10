<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Plugin\Magento\Sales\Block\Adminhtml\Order\Payment;

class HideCustomerData extends \MageSuite\Gdpr\Model\HideCustomerData\AbstractHider
{
    public function aroundToHtml(\Magento\Sales\Block\Adminhtml\Order\Payment $subject, callable $proceed)
    {
        if (!$this->customerDataVisibilityHelper->canSeeCustomerData() && $this->customerDataVisibilityHelper->shouldDataBeAnonymized()) {
            return __('Payment details are not shown. It may contain personal data.');
        }

        return $proceed();
    }
}

