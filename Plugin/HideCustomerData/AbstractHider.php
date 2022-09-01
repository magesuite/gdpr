<?php

namespace MageSuite\Gdpr\Plugin\HideCustomerData;

class AbstractHider
{
    /**
     * @var GridAnonymizationInterface[]
     */
    protected $grids = [];

    /**
     * @var \MageSuite\Gdpr\Helper\CustomerDataVisibility
     */
    protected $customerDataVisibilityHelper;

    public function __construct(\MageSuite\Gdpr\Helper\CustomerDataVisibility $customerDataVisibilityHelper)
    {
        $this->customerDataVisibilityHelper = $customerDataVisibilityHelper;
    }

    /**
     * @param $result
     * @return string
     */
    protected function getAnonymyzedString($result)
    {
        if ($this->customerDataVisibilityHelper->canSeeCustomerData() or !$this->customerDataVisibilityHelper->shouldDataBeAnonymized()) {
            return $result;
        }

        if ($this->isDate($result)) {
            return $this->getAnonymizedDate($result);
        }

        return $this->fillStringWithStars($result);
    }

    /**
     * Returns random date between 1918-01-01 and 2000-01-01
     *
     * @param $result
     * @return string
     */
    protected function getAnonymizedDate($result)
    {
        if (!$this->customerDataVisibilityHelper->canSeeCustomerData() and $this->customerDataVisibilityHelper->shouldDataBeAnonymized()) {
            return date('Y-m-d', rand(-1640966400, 946713600));
        }

        return $result;
    }

    protected function fillStringWithStars($string)
    {
        if($string === null) {
            return null;
        }

        $length = mb_strlen($string);

        if ($length < 2) {
            return $string;
        }

        return mb_substr($string, 0, 1) . str_repeat('*', $length - 1);
    }

    /**
     * @param string $gridName
     * @return bool
     */
    protected function getGridToAnonymize($gridName)
    {
        foreach ($this->grids as $grid) {
            if ($grid->canApplyToGrid($gridName)) {
                return $grid;
            }
        }
        return false;
    }

    /**
     * @param $string
     * @return bool
     */
    protected function isDate($string)
    {
        if($string === null) {
            return false;
        }

        return preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $string);
    }
}
