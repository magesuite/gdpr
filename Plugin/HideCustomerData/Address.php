<?php

namespace MageSuite\Gdpr\Plugin\HideCustomerData;

class Address extends AbstractHider
{
    public function afterGetData(\Magento\Sales\Model\Order\Address $subject, $result, $key = '', $index = null)
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
            return $this->getAnonymyzedString($result);
        }

        if (is_array($result)) {
            $result = $this->anonymyzeArray($result, $keys);
        }

        return $result;
    }

    /**
     * @param $result
     * @param $key
     * @param $keys
     * @return mixed
     */
    protected function anonymyzeArray($result, $keys)
    {
        foreach ($result as $key => $value) {
            if (!in_array($key, $keys)) {
                continue;
            }

            $result[$key] = $this->getAnonymyzedString($value);
        }
        return $result;
    }
}