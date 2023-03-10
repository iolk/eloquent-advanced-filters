<?php

namespace Iolk\EloquentAdvancedFilters\Utils;

use Nette\SmartObject;

class Paginator
{
    use SmartObject;

    public static function resolvePerPage($perPageName = 'per_page')
    {
        $perPage = request()->input($perPageName);

        // TODO: add max perPage as config?
        if (filter_var($perPage, FILTER_VALIDATE_INT) !== false && (int) $perPage >= 1) {
            return (int) $perPage;
        }

        return null;
    }

    public static function resolveColumns($columnsName = 'columns')
    {
        $columns = request()->input($columnsName);
        return $columns ?? ['*'];
    }
}
