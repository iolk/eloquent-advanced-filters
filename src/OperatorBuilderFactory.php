<?php

namespace Iolk\PaginationFspPlugin;

use Closure;
use Exception;
use Illuminate\Database\Eloquent\Builder;

class OperatorBuilderFactory
{
    public static function make(FilterOperator $operator): Closure
    {
        $methodName = 'apply' . ucfirst($operator->getOperator()) . 'Operator';
        if (!method_exists(self::class, $methodName)) {
            throw new Exception("Operator '$methodName' not yet implemented");
        }

        $factory = new self();

        $builderFn = function (Builder $builder, string $columnName, mixed $value) use ($factory, $methodName): Builder {
            return $factory->$methodName($builder, $columnName, $value);
        };

        return $builderFn;
    }

    private function applyInOperator(Builder $builder, string $columnName, mixed $value): Builder
    {
        if (!is_array($value)) {
            return $builder;
        }

        return $builder->whereIn($columnName, $value);
    }

    private function applyNotInOperator(Builder $builder, string $columnName, mixed $value): Builder
    {
        if (!is_array($value)) {
            return $builder;
        }

        return $builder->whereNotIn($columnName, $value);
    }

    private function applyEqOperator(Builder $builder, string $columnName, mixed $value): Builder
    {
        return $builder->where($columnName, '=', $value);
    }

    private function applyEqiOperator(Builder $builder, string $columnName, mixed $value): Builder
    {
        return $builder->where($columnName, '=', $value);
    }

    private function applyNeqOperator(Builder $builder, string $columnName, mixed $value): Builder
    {
        return $builder->where($columnName, '!=', $value);
    }

    private function applyGtOperator(Builder $builder, string $columnName, mixed $value): Builder
    {
        return $builder->where($columnName, '>', $value);
    }

    private function applyGteOperator(Builder $builder, string $columnName, mixed $value): Builder
    {
        return $builder->where($columnName, '>=', $value);
    }

    private function applyLtOperator(Builder $builder, string $columnName, mixed $value): Builder
    {
        return $builder->where($columnName, '<', $value);
    }

    private function applyLteOperator(Builder $builder, string $columnName, mixed $value): Builder
    {
        return $builder->where($columnName, '<=', $value);
    }

    private function applyNullOperator(Builder $builder, string $columnName, mixed $value): Builder
    {
        return $builder->whereNull($columnName);
    }

    private function applyNotNullOperator(Builder $builder, string $columnName, mixed $value): Builder
    {
        return $builder->whereNotNull($columnName);
    }

    private function applyBetweenOperator(Builder $builder, string $columnName, mixed $value): Builder
    {
        if (!is_array($value) || count($value) !== 2) {
            return $builder;
        }

        return $builder->whereBetween($columnName, $value);
    }

    private function applyNotBetweenOperator(Builder $builder, string $columnName, mixed $value): Builder
    {
        if (!is_array($value) || count($value) !== 2) {
            return $builder;
        }

        return $builder->whereNotBetween($columnName, $value);
    }

    private function applyStartsWithOperator(Builder $builder, string $columnName, mixed $value): Builder
    {
        return $builder->where($columnName, 'LIKE', "{$value}%");
    }

    private function applyNotStartsWithOperator(Builder $builder, string $columnName, mixed $value): Builder
    {
        return $builder->where($columnName, 'NOT LIKE', "{$value}%");
    }

    private function applyEndsWithOperator(Builder $builder, string $columnName, mixed $value): Builder
    {
        return $builder->where($columnName, 'LIKE', "%{$value}");
    }

    private function applyNotEndsWithOperator(Builder $builder, string $columnName, mixed $value): Builder
    {
        return $builder->where($columnName, 'NOT LIKE', "%{$value}");
    }

    private function applyStartsWithiOperator(Builder $builder, string $columnName, mixed $value): Builder
    {
        return $builder->where($columnName, 'ILIKE', "{$value}%");
    }

    private function applyNotStartsWithiOperator(Builder $builder, string $columnName, mixed $value): Builder
    {
        return $builder->where($columnName, 'NOT ILIKE', "{$value}%");
    }

    private function applyEndsWithiOperator(Builder $builder, string $columnName, mixed $value): Builder
    {
        return $builder->where($columnName, 'ILIKE', "%{$value}");
    }

    private function applyNotEndsWithiOperator(Builder $builder, string $columnName, mixed $value): Builder
    {
        return $builder->where($columnName, 'NOT ILIKE', "%{$value}");
    }

    private function applyContainsOperator(Builder $builder, string $columnName, mixed $value): Builder
    {
        return $builder->where($columnName, 'LIKE', "%{$value}%");
    }

    private function applyNotContainsOperator(Builder $builder, string $columnName, mixed $value): Builder
    {
        return $builder->where($columnName, 'NOT LIKE', "%{$value}%");
    }

    private function applyContainsiOperator(Builder $builder, string $columnName, mixed $value): Builder
    {
        return $builder->where($columnName, 'ILIKE', "%{$value}%");
    }

    private function applyNotContainsiOperator(Builder $builder, string $columnName, mixed $value): Builder
    {
        return $builder->where($columnName, 'NOT ILIKE', "%{$value}%");
    }
}
