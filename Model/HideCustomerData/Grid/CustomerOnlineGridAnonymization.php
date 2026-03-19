<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Model\HideCustomerData\Grid;

class CustomerOnlineGridAnonymization extends \MageSuite\Gdpr\Model\HideCustomerData\AbstractGridAnonymization
{
    protected array $grids = [
        'customer_online_grid'
    ];

    protected array $dataKeys = [
        'email',
        'firstname',
        'lastname'
    ];
}
