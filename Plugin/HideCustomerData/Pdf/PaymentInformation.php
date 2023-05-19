<?php

namespace MageSuite\Gdpr\Plugin\HideCustomerData\Pdf;

class PaymentInformation extends \MageSuite\Gdpr\Plugin\HideCustomerData\AbstractHider
{
    public function aroundToPdf(\Magento\Payment\Block\Info $subject, callable $proceed)
    {
        if (!$this->customerDataVisibilityHelper->canSeeCustomerData() and $this->customerDataVisibilityHelper->shouldDataBeAnonymized()) {
            return __('Payment details are not shown. It may contain personal data.');
        }

        return $proceed();
    }
}
