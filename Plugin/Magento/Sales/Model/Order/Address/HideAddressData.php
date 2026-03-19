<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Plugin\Magento\Sales\Model\Order\Address;

class HideAddressData extends \MageSuite\Gdpr\Model\HideCustomerData\AbstractHider
{
    public function afterGetData(\Magento\Sales\Model\Order\Address $subject, $result, $key = '')
    {
        if (!$this->customerDataVisibilityHelper->shouldDataBeAnonymized() or $this->customerDataVisibilityHelper->canSeeCustomerData()) {
            return $result;
        }

        $keys = [
            'firstname',
            'lastname',
            'region',
            'postcode',
            'street',
            'city',
            'email',
            'telephone',
            'company',
            'country_id'
        ];

        if (in_array($key, $keys)) {
            return $this->getAnonymizedString($result);
        }

        if (is_array($result)) {
            $result = $this->anonymizeArray($result, $keys);
        }

        return $result;
    }

    protected function anonymizeArray(array $result, array $keys): array
    {
        foreach ($result as $key => $value) {
            if (!in_array($key, $keys)) {
                continue;
            }

            $result[$key] = $this->getAnonymizedString($value);
        }

        return $result;
    }
}
