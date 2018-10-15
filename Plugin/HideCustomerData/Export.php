<?php

namespace MageSuite\Gdpr\Plugin\HideCustomerData;

class Export extends AbstractHider
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

    public function afterSearch(\Magento\Framework\View\Element\UiComponent\DataProvider\Reporting $subject, $result, \Magento\Framework\Api\Search\SearchCriteriaInterface $searchCriteria)
    {
        if (!$this->customerDataVisibilityHelper->shouldDataBeAnonymized()) {
            return $result;
        }

        $requestName = $searchCriteria->getRequestName();

        $anonymizedGrid = $this->getGridToAnonymize($requestName);

        if ($anonymizedGrid === false) {
            return $result;
        }

        $items = $result->getItems();

        /** @var \Magento\Framework\View\Element\UiComponent\DataProvider\Document $item */
        foreach ($items as $item) {
            foreach ($anonymizedGrid->getDataKeys() as $dataKey) {
                if (!$item->hasData($dataKey)) {
                    continue;
                }

                $item->setData($dataKey, $this->getAnonymyzedString($item->getData($dataKey)));
            }
        }

        return $result;
    }
}