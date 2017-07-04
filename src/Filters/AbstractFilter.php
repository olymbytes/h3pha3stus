<?php 

namespace Olymbytes\H3pha3stus\Filters;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

abstract class AbstractFilter
{
    /**
     * The model instance.
     *
     * @var Model
     */
    protected $model;

    /**
     * The query builder instance.
     *
     * @var Builder
     */
    protected $query;

    /**
     * Create a new QueryFilters instance.
     *
     * @param Request $request
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Apply the filters to the builder.
     *
     * @param  Builder $builder
     * @return Builder
     */
    public function apply(Builder $query, array $input)
    {
        $this->query = $query;
        
        $fieldsToFilter = (new FilterInputParser($this->model->getFilterableFields()))->parse($input);

        foreach ($fieldsToFilter as $filterData) {
            $column     = $filterData['key'];
            $operator   = $filterData['operator'];
            $value      = $filterData['value'];

            $tableAndColumn = $this->model->getTable() . '.' . $column;

            $filterOnMethod = camel_case($column);
            if (method_exists($this, $filterOnMethod)) {
                $this->$filterOnMethod($column, $operator, $value);
            } else if (is_array($value)) {
                $this->query->whereIn($tableAndColumn, $value);
            } else {
                $this->query->where($tableAndColumn, $operator, $value);
            }
        }

        return $this->query;
    }
}