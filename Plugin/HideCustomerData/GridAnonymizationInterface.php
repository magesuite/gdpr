<?php

namespace MageSuite\Gdpr\Plugin\HideCustomerData;

interface GridAnonymizationInterface
{
    /**
     * @param $grid
     * @return boolean
     */
    public function canApplyToGrid($grid);

    /**
     * @param $dataKey
     * @return boolean
     */
    public function canApplyToDataKey($dataKey, $grid = null);

    /**
     * @return array
     */
    public function getDataKeys();
}