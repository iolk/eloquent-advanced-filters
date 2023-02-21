<?php

namespace Iolk\EloquentAdvancedFilters;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Iolk\EloquentAdvancedFilters\Utils;
use Nette\SmartObject;

class Builder
{
    use SmartObject;

    public function __construct(protected string $modelClass, protected EloquentBuilder $query, protected array|null $customRequest = null)
    {
        if ($this->customRequest === null) {
            $this->customRequest = [];
        }
    }

    public function apply(): EloquentBuilder
    {
        $this->processFilters();
        $this->processSorting();

        return $this->query;
    }

    private function processFilters()
    {
        $filters = $this->customRequest['filters'] ?? null;

        if ($filters === null) {
            $queryData = request()->validate([
                'filters' => 'array',
                'filters.*' => 'array',
            ]);

            $filters = $queryData['filters'] ?? [];
        }

        $filtersHandler = new ModelFiltersHandler($this->modelClass);
        $filtersHandler->handle($this->query, $filters);
    }

    private function processSorting()
    {
        $sortingFields = $this->customRequest['sort'] ?? null;

        if ($sortingFields === null) {
            $queryData = request()->validate([
                'sort' => 'array',
                'sort.*' => 'alpha_dash:ascii',
            ]);

            $sortingFields = $queryData['sort'] ?? [];
        }

        /**
         * Case 1: simple array => orderBy ASC
         * Case 2: associative array => orderBy $sortValue
         */
        foreach ($sortingFields as $sortKey => $sortValue) {
            // Case 2
            $attributeName = $sortKey;
            $sortMode = strtoupper($sortValue);

            // Case 1
            if (is_numeric($sortKey)) {
                $attributeName = $sortValue;
                $sortMode = 'ASC';
            }

            if (!Utils\Model::isAttribute($this->modelClass, $attributeName)) {
                // TODO: Ignore or error?
                continue;
            }
            if (!in_array($sortMode, ['ASC', 'DESC'])) {
                // TODO: Ignore or error?
                continue;
            }

            $this->query->orderBy($attributeName, strtoupper($sortMode));
        }
    }
}
