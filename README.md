
# Eloquent Advanced Filters

Easily create REST APIs that conform to the [Microsoft API guidelines](https://github.com/microsoft/api-guidelines) using Eloquent. With this package, you can quickly filter, sort, and populate your API data using a variety of advanced filtering options.


## Installation


```
composer require iolk/eloquent-advanced-filters
```


## Usage
### Parameters

API parameters can be used to filter, sort, and paginate results and to select fields and relations to populate.

The following API parameters are available:

| Operator           | Type             | Description                                       |
| ------------------ | ---------------- | ------------------------------------------------- |
| `sort`             | String or Array  | [Sort the response](#sorting)                     |
| `filters`          | Object           | [Filter the response](#filtering)                 |
| `populate`         | String or Object | [Populate relations](#population)                 |
| `columns`          | Array            | [Select only specific columns](#field-selection)  |
| `pagination`       | Object           | [Page through entries](#pagination)               |

### Sorting

Queries can accept a `sort` parameter that allows sorting on one or multiple fields:

- `$sort = 'value'` to sort on {value} column
- `$sort = ['column1'=>'asc', 'column2'=>'desc' ...]` to sort on multiple fields

### Columns selection

Queries can accept a `columns` parameter to select only some fields. By default all the fields are selected.

### Filtering

Queries can accept a `filters` parameter with the following syntax:

`GET /{path}?filters[field][operator]=value`

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
| `$or`           | Joins the filters in an "or" expression  |
| `$and`          | Joins the filters in an "and" expression |
| `$not`          | Joins the filters in an "not" expression |

### Pagination

Queries can accept `pagination` parameters. Results can be paginated:

- either by page (i.e. specifying a page number and the number of entries per page)
- or by offset (i.e. specifying how many entries to skip and to return)


> Pagination methods can not be mixed. Always use either `page` with `pageSize` **or** `start` with `limit`.

#### Pagination by page

To paginate results by page, use the following parameters:

| Parameter               | Type    | Description                                                               | Default |
| ----------------------- | ------- | ------------------------------------------------------------------------- | ------- |
| `pagination[page]`      | Integer | Page number                                                               | 1       |
| `pagination[pageSize]`  | Integer | Page size                                                                 | 25      |


#### Pagination by offset

To paginate results by offset, use the following parameters:

| Parameter               | Type    | Description                                                    | Default |
| ----------------------- | ------- | -------------------------------------------------------------- | ------- |
| `pagination[start]`     | Integer | Start value (i.e. first entry to return)                      | 0       |
| `pagination[limit]`     | Integer | Number of entries to return                                    | 25      |
| `pagination[withCount]` | Boolean | Toggles displaying the total number of entries to the response | `true`  |


## License

EloquentAdvancedFilters is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
