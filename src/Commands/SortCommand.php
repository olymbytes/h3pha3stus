<?php 

namespace Olymbytes\H3pha3stus\Commands;

use Illuminate\Console\GeneratorCommand;

class SortCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:sort';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Sort class.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Sort';

    /**
     * Get the default namespace for the class.
     *
     * @param  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return config('h3pha3stus.sorts.namespace');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/sort.stub';
    }
}
