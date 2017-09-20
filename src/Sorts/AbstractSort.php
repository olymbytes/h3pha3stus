<?php 

namespace Olymbytes\H3pha3stus\Sorts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @todo Make a parent class that can be used for both AbstractFilter and AbstractSort.
 */
abstract class AbstractSort
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
        
        $fieldsToSort = (new SortInputParser($this->model->getSortableFields()))->parse($input);

        foreach ($fieldsToSort as $column => $direction) {
            $tableAndColumn = $this->getQualifiedColumnName($column);

            $sortOnMethod = $this->getQualifiedMethodName($column);
            if (method_exists($this, $sortOnMethod)) {
                $this->$sortOnMethod($column, $direction);
            } else {
                $this->query->orderBy($tableAndColumn, $direction);
            }
        }

        return $this->query;
    }

    protected function getQualifiedColumnName($column)
    {
        return $this->model->getTable() . '.' . $column;
    }

    protected function getQualifiedMethodName($column)
    {
        return camel_case($column);
    }
}
