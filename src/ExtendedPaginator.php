<?php

namespace Iolk\PaginationFspPlugin;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Nette\SmartObject;

class ExtendedPaginator
{
    use SmartObject;

    public function __construct(protected string $modelClass, protected Builder $builder)
    {
    }

    public function paginate(): LengthAwarePaginator
    {
        $this->prepareFilters();

        Log::debug($this->builder->toSql());

        return $this->builder->paginate();
    }

    private function prepareFilters()
    {
        $queryData = request()->validate([
            'filters' => 'array',
            'filters.*' => 'array'
        ]);

        $whereClauses = $queryData['filters'] ?? [];

        $filterApplier = new FilterApplier($this->modelClass);
        $filterApplier->processFilters($this->builder, $whereClauses);
    }
}
