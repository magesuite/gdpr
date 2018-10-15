<?php

namespace MageSuite\Gdpr\Plugin\HideCustomerData\Actions;

class MuiAction extends \MageSuite\Gdpr\Plugin\HideCustomerData\AbstractWhitelistedAction
{
    protected $whitelistedActions = [
        'mui_index_render',
        'mui_export_gridToCsv',
        'mui_export_gridToXml'
    ];
}