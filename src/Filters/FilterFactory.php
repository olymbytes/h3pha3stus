<?php 

namespace Olymbytes\H3pha3stus\Filters;

use ReflectionClass;
use Olymbytes\H3pha3stus\Support\AbstractFactory;

class FilterFactory extends AbstractFactory
{
    /**
     * Default class to instantiate.
     * 
     * @var string
     */
    protected $defaultClass = DefaultFilter::class;

    /**
     * String to append to the QualifiedModelName.
     * 
     * @var string
     */
    protected $suffix = 'Filter';

    /**
     * Get namespace from the config.
     *
     * @return string
     */
    protected function getNamespace()
    {
        return config('h3pha3stus.filters.namespace');
    }
}