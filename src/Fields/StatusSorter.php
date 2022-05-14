<?php

namespace Performing\Taskday\Status\Fields;

use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Taskday\Models\Field;

class StatusSorter
{
    public function handle(Builder $query, Field $field, bool $desc)
    {
        $options = collect($field->options)
            ->map(function ($option) {
                $color = $option['color'] ?? '';
                return "'{$color}'";
            })
            ->join(', ');

        $handle = $field->handle;

        $query->addSelect([
            'handle_value' => Field::select(['card_field.value'])
                ->whereColumn('card_field.card_id', 'cards.id')
                ->join('card_field', 'card_field.field_id', '=', 'fields.id')
                ->where('handle', $handle)
                ->take(1),
        ])
            ->orderBy(
                DB::raw("FIELD(`handle_value`, $options)"),
                $desc ? 'DESC' : 'ASC'
            );
    }
}
