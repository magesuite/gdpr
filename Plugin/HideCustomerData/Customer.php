<?php

namespace MageSuite\Gdpr\Plugin\HideCustomerData;

class Customer extends AbstractHider
{

    public function afterGetName(\Magento\Customer\Model\Customer $subject, $result)
    {
        if (!$this->customerDataVisibilityHelper->shouldDataBeAnonymized()) {
            return $result;
        }

        return $this->getAnonymyzedString($result);
    }

}
