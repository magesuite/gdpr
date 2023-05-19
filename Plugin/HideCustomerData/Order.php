<?php

namespace MageSuite\Gdpr\Plugin\HideCustomerData;

class Order extends AbstractHider
{
    public function afterGetCustomerEmail(\Magento\Sales\Model\Order $subject, $result) {
        if(!$this->customerDataVisibilityHelper->shouldDataBeAnonymized()) {
            return $result;
        }

        return $this->getAnonymyzedString($result);
    }

    public function afterGetCustomerName(\Magento\Sales\Model\Order $subject, $result) {
        if(!$this->customerDataVisibilityHelper->shouldDataBeAnonymized()) {
            return $result;
        }

        return $this->getAnonymyzedString($result);
    }
}
