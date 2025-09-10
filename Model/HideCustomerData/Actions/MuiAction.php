<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Model\HideCustomerData\Actions;

class MuiAction extends \MageSuite\Gdpr\Model\HideCustomerData\AbstractAnonymizedAction
{
    protected array $anonymizedActions = [
        'mui_index_render',
        'mui_export_gridToCsv',
        'mui_export_gridToXml'
    ];
}
