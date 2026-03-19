<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Model\HideCustomerData\Grid;

class SalesGridAnonymization extends \MageSuite\Gdpr\Model\HideCustomerData\AbstractGridAnonymization
{
    protected array $grids = [
        'sales_order_grid',
        'sales_order_view_invoice_grid',
        'sales_order_view_creditmemo_grid',
        'sales_order_view_shipment_grid',
        'sales_order_invoice_grid',
        'sales_order_shipment_grid',
        'sales_order_creditmemo_grid',
    ];

    protected array $dataKeys = [
        'shipping_name',
        'billing_name',
        'billing_address',
        'shipping_address',
        'customer_email'
    ];
}
