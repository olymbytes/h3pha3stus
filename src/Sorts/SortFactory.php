<?php 

namespace Olymbytes\H3pha3stus\Sorts;

use ReflectionClass;
use Olymbytes\H3pha3stus\Support\AbstractFactory;

class SortFactory extends AbstractFactory
{
    /**
     * Default class to instantiate.
     *
     * @var string
     */
    protected $defaultClass = DefaultSort::class;

    /**
     * String to append to the QualifiedModelName.
     *
     * @var string
     */
    protected $suffix = 'Sort';

    /**
     * Get namespace from the config.
     *
     * @return string
     */
    protected function getNamespace()
    {
        return config('h3pha3stus.sorts.namespace');
    }
}
