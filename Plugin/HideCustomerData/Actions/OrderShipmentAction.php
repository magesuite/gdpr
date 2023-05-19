<?php

namespace MageSuite\Gdpr\Plugin\HideCustomerData\Actions;

class OrderShipmentAction extends \MageSuite\Gdpr\Plugin\HideCustomerData\AbstractWhitelistedAction
{
    protected $whitelistedActions = [
        'adminhtml_order_shipment_new',
        'adminhtml_order_shipment_view'
    ];
}
