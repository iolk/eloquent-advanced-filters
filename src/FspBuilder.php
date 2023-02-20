<?php

namespace Iolk\PaginationFspPlugin;

use Illuminate\Database\Eloquent\Builder;
use Iolk\PaginationFspPlugin\Helpers\ModelHelper;
use Nette\SmartObject;

class FspBuilder
{
    use SmartObject;

    public function __construct(protected string $modelClass, protected Builder $query)
    {
    }

    public function apply(): Builder
    {
        $this->processFilters();
        $this->processSorting();
        return $this->query;
    }

    private function processFilters()
    {
        $queryData = request()->validate([
            'filters' => 'array',
            'filters.*' => 'array',
        ]);

        $whereClauses = $queryData['filters'] ?? [];

        $filterApplier = new ModelFiltersHandler($this->modelClass);
        $filterApplier->handle($this->query, $whereClauses);
    }

    private function processSorting()
    {
        $queryData = request()->validate([
            'sort' => 'array',
            'sort.*' => 'alpha:ascii',
        ]);

        $sortingFields = $queryData['sort'] ?? [];

        foreach ($sortingFields as $attributeName => $sortMode) {
            if (!ModelHelper::isAttribute($this->modelClass, $attributeName)) {
                continue;
            }
            if (!in_array(strtoupper($sortMode), ['ASC', 'DESC'])) {
                continue;
            }

            $this->query->orderBy($attributeName, strtoupper($sortMode));
        }
    }
}
