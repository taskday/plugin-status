<?php

namespace Performing\Taskday\Status;

use Taskday\Bundles\AssetBundle;
use Taskday\Base\Plugin;
use Performing\Taskday\Status\Fields\StatusField;
use Performing\Taskday\Status\Views\KanbanView;
use Performing\Taskday\Status\Filters\StatusFieldFilter;

class StatusPlugin extends Plugin
{
    public string $handle = 'status';

    public string $description;

    function bundle(): ?AssetBundle
    {
        return new StatusAssetBundle;
    }

    public function fields(): array
    {
        return [
            new StatusField()
        ];
    }

    public function views(): array
    {
        return [
            new KanbanView()
        ];
    }

    public function filters(): array
    {
        return [
            new StatusFieldFilter()
        ];
    }
}
