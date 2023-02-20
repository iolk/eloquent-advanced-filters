<?php

namespace Iolk\PaginationFspPlugin;

use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;

class FilterOperatorHandler
{
    public const GROUP_OPERATORS = ['$and', '$or'];

    public const OPERATORS = [
    //   '$not',
      '$in',
      '$notIn',
      '$eq',
      '$eqi',
      '$ne',
      '$gt',
      '$gte',
      '$lt',
      '$lte',
      '$null',
      '$notNull',
      '$between',
      '$notBetween',
      '$startsWith',
      '$notStartsWith',
      '$endsWith',
      '$notEndsWith',
      '$startsWithi',
      '$notStartsWithi',
      '$endsWithi',
      '$notEndsWithi',
      '$contains',
      '$notContains',
      '$containsi',
      '$notContainsi'
    ];

    public const CAST_OPERATORS = [
    //   '$not',
      '$in',
      '$notIn',
      '$eq',
      '$ne',
      '$gt',
      '$gte',
      '$lt',
      '$lte',
      '$between'
    ];

    public const ARRAY_OPERATORS = ['$in', '$notIn', '$between'];

    public function __construct(private string $operator)
    {
        if (!self::isOperator($this->operator) && !self::isGroupOperator($this->operator)) {
            throw new Exception("Invalid filter operator '{$this->operator}'");
        }

        $this->operator = substr($this->operator, 1);
    }

    public function getOperator()
    {
        return $this->operator;
    }

    public function handle(Builder $builder, string $attributeName, mixed $value): Builder
    {
        $applyFn = OperatorBuilderFactory::make($this);
        return $applyFn($builder, $attributeName, $value);
    }

    public static function isOperator(string $key)
    {
        return in_array($key, self::OPERATORS);
    }

    public static function isGroupOperator(string $key)
    {
        return in_array($key, self::GROUP_OPERATORS);
    }

    public static function isArrayOperator(string $key)
    {
        return in_array($key, self::ARRAY_OPERATORS);
    }

    public static function isCastOperator(string $key)
    {
        return in_array($key, self::CAST_OPERATORS);
    }
}
