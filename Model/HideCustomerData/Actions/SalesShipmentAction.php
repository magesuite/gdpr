<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Model\HideCustomerData\Actions;

class SalesShipmentAction extends \MageSuite\Gdpr\Model\HideCustomerData\AbstractAnonymizedAction
{
    protected array $anonymizedActions = [
        'sales_shipment_print'
    ];
}
