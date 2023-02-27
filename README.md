
# Eloquent Advanced Filters (WIP)

Easily create REST APIs that conform to the [Microsoft API guidelines](https://github.com/microsoft/api-guidelines) using Eloquent. With this package, you can quickly filter, sort, and populate your API data using a variety of advanced filtering options.

## Installation

You can install the package using Composer:

```
composer require iolk/eloquent-advanced-filters
```

## Usage

To use the package, you can call various methods on your Eloquent model to paginate and/or filter the results.

| Operator                        | Description                                                            |
| ------------------------------- | ---------------------------------------------------------------------- |
| `applyAdvancedFilters`          | Applies filters and sorting without pagination                         |
| `paginateWithFilters()`         | Applies filters and sorting using Eloquent `paginate()` method         |
| `cursorPaginateWithFilters()`   | Applies filters and sorting using Eloquent `cursorPaginate()` method   |
| `simplePaginateWithFilters()`   | Applies filters and sorting using Eloquent `simplePaginate()` method   |

 Here's an example with `paginateWithFilters()`:

```php
public function list()
{
    return User::paginateWithFilters();
}
```

You can then make queries to your API like this:

```
/users?filters[posts][likes][$gte]=25&sort=name
```

The request payload for this query would be:

```php
[
    "filters" => [
        "posts" => [
            "likes" => [
                "\$gte" => 25
            ]
        ]
    ],
    "sort" => "name"
]
```

## Parameters

 ### Sort

The sort parameter allows you to sort your API response by one or multiple fields.
```php
// Single field sorting
[
    "sort" => "value"
]

// Multiple field sorting
[
    "sort" => [
        "column1" => "asc",
        "column2" => "desc"
    ]
]
```

### Columns

The columns parameter allows you to select only specific columns to return in your API response. By default, all columns are selected. You can use it like this:

```php
[
    "columns" => ["column1", "column2", ...]
]

```

### Filters
The filters parameter allows you to filter your API response based on specific conditions. You can use it like this:

```php
[
    "filters" => [
        "column1" => [
            "$eg" => "value"
        ]
    ]
]

```

The following operators are available:

| Operator        | Description                              |
| --------------- | ---------------------------------------- |
| `$eq`           | Equal                                    |
| `$eqi`          | Equal (case-insensitive)                 |
| `$ne`           | Not equal                                |
| `$lt`           | Less than                                |
| `$lte`          | Less than or equal to                    |
| `$gt`           | Greater than                             |
| `$gte`          | Greater than or equal to                 |
| `$in`           | Included in an array                     |
| `$notIn`        | Not included in an array                 |
| `$contains`     | Contains                                 |
| `$notContains`  | Does not contain                         |
| `$containsi`    | Contains (case-insensitive)              |
| `$notContainsi` | Does not contain (case-insensitive)      |
| `$null`         | Is null                                  |
| `$notNull`      | Is not null                              |
| `$between`      | Is between                               |
| `$startsWith`   | Starts with                              |
| `$startsWithi`  | Starts with (case-insensitive)           |
| `$endsWith`     | Ends with                                |
| `$endsWithi`    | Ends with (case-insensitive)             |

You have also the following group operators:

| Operator        | Description      |
| --------------- | ---------------- |
| `$or`           | Or               |
| `$and`          | And              |
| `$not`          | Not              |

## License

Eloquent Advanced Filters is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
