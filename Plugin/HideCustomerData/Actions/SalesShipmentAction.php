<?php

namespace MageSuite\Gdpr\Plugin\HideCustomerData\Actions;

class SalesShipmentAction extends \MageSuite\Gdpr\Plugin\HideCustomerData\AbstractWhitelistedAction
{
    protected $whitelistedActions = [
        'sales_shipment_print'
    ];
}