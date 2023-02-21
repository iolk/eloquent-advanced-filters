<?php

namespace Iolk\EloquentAdvancedFilters\Utils;

use Exception;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Facades\Schema;

class Model
{
    public static function getModelInstance(string $className): EloquentModel
    {
        if (!in_array(EloquentModel::class, class_parents($className))) {
            throw new Exception("Model helper called on non '".EloquentModel::class."' class: '$className'");
        }
        return new ($className)();
    }

    public static function getAttributes(string $className): array
    {
        $modelInstance = self::getModelInstance($className);
        return Schema::getColumnListing($modelInstance->getTable());
    }

    public static function getFilterableRelations(string $className): array
    {
        return $className::getFilterableRelations();
    }

    public static function getRelationClass(string $className, string $relationName)
    {
        if (!self::isFilterableRelation($className, $relationName)) {
            throw new Exception("$relationName is not a filterable relation of $className, ensure that the \$filterableRelations of the model contains it");
        }

        $modelInstance = self::getModelInstance($className);
        return get_class($modelInstance->{$relationName}()->getRelated());
    }

    public static function isFilterableRelation(string $className, string $relationName)
    {
        return in_array($relationName, self::getFilterableRelations($className));
    }

    public static function isAttribute(string $className, string $attributeName)
    {
        return in_array($attributeName, self::getAttributes($className));
    }
}
