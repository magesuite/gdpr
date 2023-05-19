<?php

namespace MageSuite\Gdpr\Plugin\HideCustomerData\Export;

class SalesGridExportAnonymization extends \MageSuite\Gdpr\Plugin\HideCustomerData\AbstractGridAnonymization
{
    protected $grids = [
        'sales_order_grid_data_source',
        'sales_order_view_shipment_grid_data_source',
        'sales_order_view_invoice_grid_data_source',
        'sales_order_view_creditmemo_grid_data_source',
        'sales_order_invoice_grid_data_source',
        'sales_order_shipment_grid_data_source',
        'sales_order_creditmemo_grid_data_source'
    ];

    protected $dataKeys = [
        'shipping_name',
        'billing_name',
        'billing_address',
        'shipping_address',
        'customer_email',
        'customer_name'
    ];
}
