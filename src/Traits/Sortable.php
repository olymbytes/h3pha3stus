<?php

namespace Olymbytes\H3pha3sus\Traits;

use Olymbytes\H3pha3stus\Sorts\SortFactory;

trait Sortable
{
    /**
     * The attributes that can be used to sort.
     *
     * @return array
     */
    abstract public function getSortableFields();

    /**
     * Scope a query to sort.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortable($query, array $input)
    {
        return (new SortFactory($this))->make()->apply($query, $input);
    }
}
