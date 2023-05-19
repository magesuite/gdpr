<?php

namespace MageSuite\Gdpr\Plugin\HideCustomerData;

class AbstractWhitelistedAction
{
    protected $whitelistedActions = [];

    /**
     * @return array
     */
    public function getWhitelistedActions()
    {
        return $this->whitelistedActions;
    }
}
