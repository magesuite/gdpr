<?php

namespace MageSuite\Gdpr\Plugin\HideCustomerData\Grid;

class CustomerGridAnonymization extends \MageSuite\Gdpr\Plugin\HideCustomerData\AbstractGridAnonymization
{
    protected $grids = [
        'customer_listing'
    ];

    protected $dataKeys = [
        'name',
        'email',
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
        'taxvat'
    ];
}
