<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Model\HideCustomerData\Actions;

class CustomerAction extends \MageSuite\Gdpr\Model\HideCustomerData\AbstractAnonymizedAction
{
    protected array $anonymizedActions = [
        'customer_index_edit'
    ];
}
