<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Plugin\Magento\Sales\Model\Order;

class HideCustomerData extends \MageSuite\Gdpr\Model\HideCustomerData\AbstractHider
{
    public function afterGetCustomerEmail(\Magento\Sales\Model\Order $subject, $result): ?string
    {
        if (!$this->customerDataVisibilityHelper->shouldDataBeAnonymized()) {
            return $result;
        }

        return $this->getAnonymizedString($result);
    }

    public function afterGetCustomerName(\Magento\Sales\Model\Order $subject, $result): ?string
    {
        if (!$this->customerDataVisibilityHelper->shouldDataBeAnonymized()) {
            return $result;
        }

        return $this->getAnonymizedString($result);
    }
}
