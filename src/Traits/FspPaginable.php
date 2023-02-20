<?php

namespace Iolk\PaginationFspPlugin\Traits;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Iolk\PaginationFspPlugin\FspBuilder;
use Iolk\PaginationFspPlugin\FspPaginator;

trait FspPaginable
{
    /**
     * Creates local scope to run the filter.
     *
     * @param $query
     */
    public function scopeFsp($query)
    {
        $fsp = new FspBuilder(self::class, $query);
        return $fsp->apply();
    }

    /**
     * Paginate the given query.
     *
     * @param  int|null|\Closure  $perPage
     * @param  array|string  $columns
     * @param  string  $pageName
     * @param  int|null  $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     *
     * @throws \InvalidArgumentException
     */
    public function scopeFspPaginate(Builder $query, $perPage = null, $columns = null, $pageName = 'page', $page = null): LengthAwarePaginator
    {
        $perPage = $perPage ?: FspPaginator::resolvePerPage();
        $columns = $columns ?: FspPaginator::resolveColumns();
        return $query->fsp()->paginate($perPage, $columns, $pageName, $page);
    }

    /**
     * Paginate the given query into a cursor paginator.
     *
     * @param  int|null  $perPage
     * @param  array|string  $columns
     * @param  string  $cursorName
     * @param  \Illuminate\Pagination\Cursor|string|null  $cursor
     * @return \Illuminate\Contracts\Pagination\CursorPaginator
     */
    public function scopeFspCursorPaginate(Builder $query, $perPage = null, $columns = null, $cursorName = 'cursor', $cursor = null): CursorPaginator
    {
        $perPage = $perPage ?: FspPaginator::resolvePerPage();
        $columns = $columns ?: FspPaginator::resolveColumns();
        return $query->fsp()->cursorPaginate($perPage, $columns, $cursorName, $cursor);
    }

    /**
     * Paginate the given query into a simple paginator.
     *
     * @param  int|null  $perPage
     * @param  array|string  $columns
     * @param  string  $pageName
     * @param  int|null  $page
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function scopeFspSimplePaginateFilter(Builder $query, $perPage = null, $columns = null, $pageName = 'page', $page = null)
    {
        $perPage = $perPage ?: FspPaginator::resolvePerPage();
        $columns = $columns ?: FspPaginator::resolveColumns();
        return $query->fsp()->simplePaginate($perPage, $columns, $pageName, $page);
    }

    public static function getFilterableRelations(): array
    {
        $obj = new static();
        if (!property_exists($obj, 'filterableRelations')) {
            return [];
        }
        return $obj->filterableRelations;
    }
}
