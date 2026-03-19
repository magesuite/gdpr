<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Helper;

class CustomerDataVisibility
{
    public function __construct(
        protected \Magento\Framework\AuthorizationInterface $authorization,
        protected \Magento\Framework\App\Request\Http $request,
        protected \Magento\Framework\App\State $state,
        protected \MageSuite\Gdpr\Model\Authorization\CanAccessCustomerData $canAccessCustomerData,
        /** @var \MageSuite\Gdpr\Model\HideCustomerData\AbstractAnonymizedAction[] $anonymizedActionsProviders */
        protected array $anonymizedActionsProviders = []
    ) {}

    public function canSeeCustomerData(): bool
    {
        return $this->canAccessCustomerData->execute();
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
