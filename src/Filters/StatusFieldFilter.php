<?php

namespace Performing\Taskday\Status\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Taskday\Base\Operator;
use Taskday\Base\Filter;
use Taskday\Models\Field;
use Taskday\Models\Card;
use Illuminate\Support\Facades\Auth;

class StatusFieldFilter extends Filter
{
    protected $name = 'Status';

    /** @inheritdoc */
    public function boot(): void
    {
        Card::addGlobalScope($this);
    }

    public function columns(): array
    {
        return Field::whereHas('projects', function ($projects) {
            $projects->whereIn('id', Auth::user()->sharedProjects->pluck('id'));
        })
        ->where('type', 'status')
        ->get()
        ->toArray();
    }

    /** @inheritdoc */
    public function operators(): array
    {
        return [
            Operator::IS_EQUAL,
            Operator::IS_NOT_EQUAL,
            Operator::PRESENT
        ];
    }

    public function handler(Operator $operator): \Closure
    {
        return match ($operator) {
            Operator::IS_EQUAL => function (Builder $builder, string $handle, string $value) {
                $builder->whereHas('fields', function ($q) use ($handle, $value) {
                    $q->where('handle', $handle)->where('value', $value);
                });
            },
            Operator::IS_NOT_EQUAL => function (Builder $builder, string $handle, string $value) {
                $builder->whereHas('fields', function ($q) use ($handle, $value) {
                    $q->where('handle', $handle)->where('value', '<>', $value);
                });
            },
            Operator::PRESENT => function (Builder $builder, string $handle) {
                $builder->whereHas('fields', function ($q) use ($handle) {
                    $q->where('handle', $handle);
                });
            },
            default => function () { }
        };
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $fields = Field::select('handle')->where('type', 'status')->pluck('handle')->toArray();

        if (request()->has('filters')) {
            foreach (request()->input('filters') as $filter) {
                if ($filter['type'] == "status-field" && in_array($filter['column'], $fields)) {
                    try {
                        [ 'column' => $handle, 'value' => $value, 'operator' => $operator ] = $filter;
                        $handler = $this->handler(Operator::from($operator));
                        call_user_func($handler, $builder, $handle, $value);
                    } catch (\Throwable $e) {
                        
                    }
                }
            }
        }
    }
}
