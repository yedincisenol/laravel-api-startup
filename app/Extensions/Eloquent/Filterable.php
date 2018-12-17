<?php

namespace App\Extensions\Eloquent;

trait Filterable
{
    /**
     * Trigger the query builder scope.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $queryString
     *
     * @return void
     */
    public function scopeFilter($query, $queryString = null)
    {
        value(new Filter($this, $query))->build($queryString);
    }
}
