<?php

namespace Performing\Taskday\Status\Widgets;

use Taskday\Models\Card;
use Taskday\Base\Filter;
use Performing\Taskday\Status\Fields\StatusField;

class BacklogWidget
{
    public $title = 'Backlog';

    public $handle = 'backlog-cards';

    public function toArray()
    {
        return [
            'title' => $this->title,
            'props' => $this->props()
        ];
    }

    public function props()
    {
        return [
            'cards' => Card::withFieldFilter(StatusField::class, Filter::IS_EQUAL, StatusField::BACKLOG)
                ->with('project.workspace')
                ->orderBy('updated_at', 'desc')
                ->limit(7)
                ->get()
        ];
    }
}
