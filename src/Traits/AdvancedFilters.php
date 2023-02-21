<?php

namespace Iolk\EloquentAdvancedFilters\Traits;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Iolk\EloquentAdvancedFilters\Builder as AdvancedBuilder;
use Iolk\EloquentAdvancedFilters\Utils;

trait AdvancedFilters
{
    /**
     * Creates local scope to run the filter.
     *
     * @param array $customRequest custom array with filters, sort and populate, if present it will override the request query
     */
    public function scopeApplyAdvancedFilters(Builder $query, $customRequest = [])
    {
        $fsp = new AdvancedBuilder(self::class, $query, $customRequest);
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
    public function scopePaginateWithFilters(Builder $query, $customRequest = [], $perPage = null, $columns = null, $pageName = 'page', $page = null): LengthAwarePaginator
    {
        $perPage = $perPage ?: Utils\Paginator::resolvePerPage();
        $columns = $columns ?: Utils\Paginator::resolveColumns();
        return $query->applyAdvancedFilters($customRequest)->paginate($perPage, $columns, $pageName, $page);
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
    public function scopeCursorPaginateWithFilters(Builder $query, $customRequest = [], $perPage = null, $columns = null, $cursorName = 'cursor', $cursor = null): CursorPaginator
    {
        $perPage = $perPage ?: Utils\Paginator::resolvePerPage();
        $columns = $columns ?: Utils\Paginator::resolveColumns();
        return $query->applyAdvancedFilters($customRequest)->cursorPaginate($perPage, $columns, $cursorName, $cursor);
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
    public function scopeSimplePaginateWithFilters(Builder $query, $customRequest = [], $perPage = null, $columns = null, $pageName = 'page', $page = null)
    {
        $perPage = $perPage ?: Utils\Paginator::resolvePerPage();
        $columns = $columns ?: Utils\Paginator::resolveColumns();
        return $query->applyAdvancedFilters($customRequest)->simplePaginate($perPage, $columns, $pageName, $page);
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
