<?php

namespace MageSuite\Gdpr\Helper;

class CustomerDataVisibility
{

    const HIDE_CUSTOMER_DATA_RESOURCE = 'MageSuite_Gdpr::hide_customer_data';

    /**
     * Actions that should return anonymised data
     * @var \MageSuite\Gdpr\Plugin\HideCustomerData\AbstractWhitelistedAction[]
     */
    protected $whitelistedActions = [];

    /**
     * @var \Magento\Framework\AuthorizationInterface
     */
    protected $authorization;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    public function __construct(
        \Magento\Framework\AuthorizationInterface $authorization,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\App\State $state,
        array $whitelistedActions
    )
    {
        $this->authorization = $authorization;
        $this->request = $request;
        $this->state = $state;
        $this->whitelistedActions = $whitelistedActions;
    }

    public function canSeeCustomerData()
    {
        return !$this->authorization->isAllowed(self::HIDE_CUSTOMER_DATA_RESOURCE);
    }

    public function shouldDataBeAnonymized()
    {
        $areaCode = $this->state->getAreaCode();

        if ($areaCode !== 'adminhtml') {
            return false;
        }

        $action = $this->request->getFullActionName();

        foreach ($this->whitelistedActions as $actionsList) {
            if (in_array($action, $actionsList->getWhitelistedActions())) {
                return true;
            }
        }
        return false;
    }
}
