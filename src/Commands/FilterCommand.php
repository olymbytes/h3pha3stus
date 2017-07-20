<?php 

namespace Olymbytes\H3pha3stus\Commands;

use Illuminate\Console\GeneratorCommand;

class FilterCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:filter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Filter class.';

    /**
     * The type of class being generated.
     * 
     * @var string
     */
    protected $type = 'Filter';

    /**
     * Get the default namespace for the class.
     * 
     * @param  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return config('h3pha3stus.filters.namespace');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/filter.stub';
    }
}