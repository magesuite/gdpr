<?php

namespace MageSuite\Gdpr\Plugin\HideCustomerData;

class PaymentInformation extends AbstractHider
{
    public function aroundToHtml(\Magento\Sales\Block\Adminhtml\Order\Payment $subject, callable $proceed) {
        if(!$this->customerDataVisibilityHelper->canSeeCustomerData() and $this->customerDataVisibilityHelper->shouldDataBeAnonymized()) {
            return __('Payment details are not shown. It may contain personal data.');
        }

        return $proceed();
    }

}
