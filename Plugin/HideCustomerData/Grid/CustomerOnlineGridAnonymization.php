<?php

namespace MageSuite\Gdpr\Plugin\HideCustomerData\Grid;

class CustomerOnlineGridAnonymization extends \MageSuite\Gdpr\Plugin\HideCustomerData\AbstractGridAnonymization
{
    protected $grids = [
        'customer_online_grid'
    ];

    protected $dataKeys = [
        'email',
        'firstname',
        'lastname'
    ];
}