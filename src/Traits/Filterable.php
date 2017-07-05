<?php

namespace Olymbytes\H3pha3stus\Traits;

use Olymbytes\H3pha3stus\Filters\FilterFactory;

trait Filterable
{
    /**
     * The attributes that can be used to filter.
     *
     * @return array
     */
    abstract public function getFilterableFields();

    /**
     * Scope a query to sort.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterable($query, array $input)
    {
        return (new FilterFactory($this))->make()->apply($query, $input);
    }
}
