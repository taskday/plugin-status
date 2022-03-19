<?php

namespace Performing\Taskday\Status;

use Taskday\Bundles\AssetBundle;

class StatusAssetBundle extends AssetBundle
{
    function scripts(): array
    {
        return [
            __DIR__ . '/../dist/taskday-status.es.js'
        ];
    }

    function styles(): array
    {
        return [
            //
        ];
    }
}
