<?php

namespace MageSuite\Gdpr\Plugin\HideCustomerData\Export;

class CustomerGridExportAnonymization extends \MageSuite\Gdpr\Plugin\HideCustomerData\AbstractGridAnonymization
{
    protected $grids = [
        'customer_listing_data_source'
    ];

    protected $dataKeys = [
        'billing_firstname',
        'billing_lastname',
        'billing_telephone',
        'billing_postcode',
        'billing_region',
        'billing_street',
        'billing_city',
        'billing_fax',
        'billing_vat_id',
        'billing_company',
        'dob',
        'taxvat',
        'customer_email',
        'name',
        'email',
        'billing_full',
        'shipping_full'

    ];
}
