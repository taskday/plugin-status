<?php

namespace Performing\Taskday\Status\Fields;

use Taskday\Base\Field;
class StatusField extends Field
{
    public function getSorter() 
    {
        return new StatusSorter();
    }
}
