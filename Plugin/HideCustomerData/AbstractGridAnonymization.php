<?php

namespace MageSuite\Gdpr\Plugin\HideCustomerData;

abstract class AbstractGridAnonymization implements GridAnonymizationInterface
{
    protected $grids = [];

    protected $dataKeys = [];

    /**
     * @inheritdoc
     */
    public function canApplyToGrid($grid)
    {
        return in_array($grid, $this->grids);
    }

    /**
     * @inheritdoc
     */
    public function canApplyToDataKey($dataKey, $grid = null)
    {
        if ($grid !== null) {
            return in_array($dataKey, $this->dataKeys) && in_array($grid, $this->grids);
        } else {
            return in_array($dataKey, $this->dataKeys);
        }
    }

    /**
     * @return array
     */
    public function getDataKeys()
    {
        return $this->dataKeys;
    }
}
