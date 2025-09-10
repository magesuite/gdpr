<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Model\HideCustomerData\Actions;

class SalesOrderAction extends \MageSuite\Gdpr\Model\HideCustomerData\AbstractAnonymizedAction
{
    protected array $anonymizedActions = [
        'sales_order_view',
        'sales_order_invoice_new',
        'sales_order_invoice_view',
        'sales_order_invoice_print',
        'sales_order_creditmemo_new',
        'sales_order_creditmemo_view',
        'sales_order_creditmemo_print',
        'sales_order_address'
    ];
}
