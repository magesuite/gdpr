<?php

namespace MageSuite\Gdpr\Plugin\HideCustomerData\Actions;

class SalesOrderAction extends \MageSuite\Gdpr\Plugin\HideCustomerData\AbstractWhitelistedAction
{
    protected $whitelistedActions = [
        'sales_order_view',
        'sales_order_invoice_new',
        'sales_order_invoice_view',
        'sales_order_invoice_print',
        'sales_order_creditmemo_new',
        'sales_order_creditmemo_view',
        'sales_order_creditmemo_print'
    ];
}