<?php

namespace Iolk\PaginationFspPlugin\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Iolk\PaginationFspPlugin\ExtendedPaginator;

trait Paginable
{
    public static function getFilterableRelations(): array
    {
        $obj = new static();
        if (!property_exists($obj, 'filterableRelations')) {
            return [];
        }
        return $obj->filterableRelations;
    }

    public static function advancePaginate(): LengthAwarePaginator
    {
        $paginator = new ExtendedPaginator(self::class, self::query());

        return $paginator->paginate();
    }
}
