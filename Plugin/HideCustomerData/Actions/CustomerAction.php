<?php

namespace MageSuite\Gdpr\Plugin\HideCustomerData\Actions;

class CustomerAction extends \MageSuite\Gdpr\Plugin\HideCustomerData\AbstractWhitelistedAction
{
    protected $whitelistedActions = [
        'customer_index_edit'
    ];
}