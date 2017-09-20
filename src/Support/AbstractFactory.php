<?php 

namespace Olymbytes\H3pha3stus\Support;

use ReflectionClass;
use Olymbytes\H3pha3stus\Contracts\Factory as FactoryContract;

abstract class AbstractFactory implements FactoryContract
{
    /**
     * Default class to instantiate if nothing is found.
     *
     * @var string|boolean
     */
    protected $defaultClass = false;

    /**
     * The model instance.
     *
     * @var Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * String to append to the QualifiedModelName.
     *
     * @var string
     */
    protected $suffix;

    /**
     * Sets the model instance on the factory.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Get the model name.
     *
     * @return string
     */
    protected function getModelName()
    {
        return (new ReflectionClass($this->model))->getShortName();
    }

    /**
     * Get namespace from the config.
     *
     * @return string
     */
    abstract protected function getNamespace();

    /**
     * Get the full path of the model.
     *
     * @return string
     */
    protected function getQualifiedModelName()
    {
        return  $this->getNamespace() . "\\" . $this->getModelName() . $this->suffix;
    }

    /**
     * Instantiate the sort.
     *
     * @return Olymbytes\H3pha3stus\Sorts\AbstractSort
     */
    public function make()
    {
        if (class_exists($this->getQualifiedModelName())) {
            return (new ReflectionClass($this->getQualifiedModelName()))->newInstance($this->model);
        }

        if ($this->defaultClass) {
            return (new ReflectionClass($this->defaultClass))->newInstance($this->model);
        }
    }
}
