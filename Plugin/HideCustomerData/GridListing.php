<?php

namespace MageSuite\Gdpr\Plugin\HideCustomerData;

class GridListing extends AbstractHider
{

    /**
     * GridListing constructor.
     * @param GridAnonymizationInterface[] $grids
     */
    public function __construct(\MageSuite\Gdpr\Helper\CustomerDataVisibility $customerDataVisibilityHelper, array $grids = [])
    {
        $this->grids = $grids;
        parent::__construct($customerDataVisibilityHelper);
    }


    public function afterGetDataSourceData(\Magento\Ui\Component\Listing $subject, $result)
    {
        if(!$this->customerDataVisibilityHelper->shouldDataBeAnonymized()) {
            return $result;
        }

        $name = $subject->getData('name');

        $anonymizedGrid = $this->getGridToAnonymize($name);

        if ($anonymizedGrid === false || !isset($result['data']['items']) || !is_array($result['data']['items'])) {
            return $result;
        }

        foreach ($result['data']['items'] as &$item) {
            foreach (array_keys($item) as $dataKey) {
                if (!$anonymizedGrid->canApplyToDataKey($dataKey, $name)) {
                    continue;
                }
                $item[$dataKey] = $this->getAnonymyzedString($item[$dataKey]);
            }
        }

        return $result;
    }
}