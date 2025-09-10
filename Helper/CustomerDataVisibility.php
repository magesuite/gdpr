<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Helper;

class CustomerDataVisibility
{
    public const SHOW_CUSTOMER_DATA_RESOURCE = 'MageSuite_Gdpr::show_customer_data';

    public function __construct(
        protected \Magento\Framework\AuthorizationInterface $authorization,
        protected \Magento\Framework\App\Request\Http $request,
        protected \Magento\Framework\App\State $state,
        /** @var \MageSuite\Gdpr\Plugin\HideCustomerData\AbstractAnonymizedAction[] $anonymizedActionsProviders */
        protected array $anonymizedActionsProviders
    ) {}

    public function canSeeCustomerData(): bool
    {
        return $this->authorization->isAllowed(self::SHOW_CUSTOMER_DATA_RESOURCE);
    }

    public function shouldDataBeAnonymized(): bool
    {
        try {
            $areaCode = $this->state->getAreaCode();
        } catch (\Exception) {
            return false;
        }

        if ($areaCode !== \Magento\Framework\App\Area::AREA_ADMINHTML) {
            return false;
        }

        $action = $this->request->getFullActionName();

        foreach ($this->anonymizedActionsProviders as $actionsList) {
            if (in_array($action, $actionsList->getAnonymizedActions())) {
                return true;
            }
        }

        return false;
    }
}
