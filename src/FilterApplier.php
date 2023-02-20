<?php

namespace Iolk\PaginationFspPlugin;

use Exception;
use Nette\SmartObject;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class FilterApplier
{
    use SmartObject;

    private $modelInstance = null;
    private $modelAttributes = [];
    private $modelRelations = [];

    public function __construct(protected string $modelClass)
    {
        if (!in_array('Illuminate\Database\Eloquent\Model', class_parents($this->modelClass))) {
            throw new Exception('Filters can be applied only on Illuminate\Database\Eloquent\Model');
        }

        $this->modelInstance = new ($this->modelClass)();
        $this->modelAttributes = Schema::getColumnListing($this->modelInstance->getTable());
        $this->modelRelations = $this->modelInstance->getFilterableRelations();
    }

    public function processFilters(Builder $builder, array $whereClauses)
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
                    $this->processFilters($q, $whereValue);
                });
                continue;
            }

            if (FilterOperator::isOperator($whereKey)) {
                throw new Exception(
                    "Only \$and, \$or and \$not can only be used as root level operators. Found $whereKey."
                );
            }

            if ($this->isAttribute($whereKey)) {
                $this->processAttributeFilter($builder, $whereKey, $whereValue);
                continue;
            }

            if ($this->isRelation($whereKey)) {
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

        $operator = new FilterOperator($keys[0]);
        return $operator->applyFilter($builder, $attributeName, $filter[$keys[0]]);
    }

    private function processRelationFilter(Builder $builder, string $relationName, array $whereClauses)
    {
        $builder->whereHas($relationName, function ($q) use ($relationName, $whereClauses) {
            $relationApplier = new FilterApplier($this->getRelationClass($relationName));
            $relationApplier->processFilters($q, $whereClauses);
        });
    }

    private function processGroupOperator(Builder $builder, string $groupFnName, array $groupWhereClauses)
    {
        $builder->where(function ($q) use ($groupFnName, $groupWhereClauses) {
            foreach ($groupWhereClauses as $groupWhereClause) {
                $q->{$groupFnName}(function ($q) use ($groupWhereClause) {
                    $this->processFilters($q, $groupWhereClause);
                });
            }
        });
    }

    public function getRelationClass(string $relationName)
    {
        return get_class($this->modelInstance->{$relationName}()->getRelated());
    }

    private function isRelation(string $key)
    {
        return in_array($key, $this->modelRelations);
    }

    private function isAttribute(string $key)
    {
        return in_array($key, $this->modelAttributes);
    }
}
