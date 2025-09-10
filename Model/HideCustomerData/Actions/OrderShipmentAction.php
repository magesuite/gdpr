<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Model\HideCustomerData\Actions;

class OrderShipmentAction extends \MageSuite\Gdpr\Model\HideCustomerData\AbstractAnonymizedAction
{
    protected array $anonymizedActions = [
        'adminhtml_order_shipment_new',
        'adminhtml_order_shipment_view'
    ];
}
