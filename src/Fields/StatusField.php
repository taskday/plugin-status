<?php

namespace Performing\Taskday\Status\Fields;

use Taskday\Base\Field;

class StatusField extends Field
{
    const BACKLOG = 'gray';
    const TODO = 'RED';
    const INPROGRESS = 'yellow';
    const COMPLETED = 'GREEN';

    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
