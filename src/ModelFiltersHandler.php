<?php

namespace Iolk\PaginationFspPlugin;

use Exception;
use Nette\SmartObject;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Iolk\PaginationFspPlugin\Helpers\ModelHelper;

class ModelFiltersHandler
{
    use SmartObject;

    public function __construct(protected string $modelClass)
    {
    }

    public function handle(Builder $builder, array $whereClauses)
    {
        foreach ($whereClauses as $whereKey => $whereValue) {
            if ($whereKey === '$or') {
                $this->processGroupOperator($builder, 'orWhere', $whereValue);
                continue;
            }
            if ($whereKey === '$and') {
                $this->processGroupOperator($builder, 'where', $whereValue);
                continue;
            }

            if ($whereKey === '$not') {
                if (!is_array($whereValue) || !Arr::isAssoc($whereValue)) {
                    throw new Exception(
                        "\$not operator requires only associative arrays."
                    );
                }
                $builder->whereNot(function ($q) use ($whereValue) {
                    $this->handle($q, $whereValue);
                });
                continue;
            }

            if (FilterOperatorHandler::isOperator($whereKey)) {
                throw new Exception(
                    "Only \$and, \$or and \$not can only be used as root level operators. Found $whereKey."
                );
            }

            if (ModelHelper::isAttribute($this->modelClass, $whereKey)) {
                $this->processAttributeFilter($builder, $whereKey, $whereValue);
                continue;
            }

            if (ModelHelper::isFilterableRelation($this->modelClass, $whereKey)) {
                $this->processRelationFilter($builder, $whereKey, $whereValue);
                continue;
            }

            throw new Exception("Unknown key in filters '$whereKey'");
        }
    }

    private function processAttributeFilter(Builder $builder, string $attributeName, array $filter)
    {
        $keys = array_keys($filter);

        if (empty($filter) || count($filter)>1) {
            throw new Exception("Unprocessable filter for attribute '$attributeName'");
        }

        $operator = new FilterOperatorHandler($keys[0]);
        return $operator->handle($builder, $attributeName, $filter[$keys[0]]);
    }

    private function processRelationFilter(Builder $builder, string $relationName, array $whereClauses)
    {
        $builder->whereHas($relationName, function ($q) use ($relationName, $whereClauses) {
            $relationClass = ModelHelper::getRelationClass($this->modelClass, $relationName);

            $relationApplier = new ModelFiltersHandler($relationClass);
            $relationApplier->handle($q, $whereClauses);
        });
    }

    private function processGroupOperator(Builder $builder, string $groupFnName, array $groupWhereClauses)
    {
        $builder->where(function ($q) use ($groupFnName, $groupWhereClauses) {
            foreach ($groupWhereClauses as $groupWhereClause) {
                $q->{$groupFnName}(function ($q) use ($groupWhereClause) {
                    $this->handle($q, $groupWhereClause);
                });
            }
        });
    }
}
