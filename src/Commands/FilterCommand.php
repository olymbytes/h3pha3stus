<?php 

namespace Olymbytes\H3pha3stus\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Mustache_Engine;

class FilterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:filter {name} {--path=app/Filters}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new filter class.';

    /**
     * The filesystem instance.
     * @var Illuminate\Filesystem\Filesystem
     */
    protected $file;

    /**
     * The mustache engine instance.
     * @var Mustache_Engine
     */
    protected $mustache;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $file, Mustache_Engine $mustache)
    {
        parent::__construct();
        $this->file = $file;
        $this->mustache = $mustache;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $path = $this->option('path');

        // Retrieve the template file
        $template = $this->file->get(__DIR__ . '/FilterCommand.stub');

        // Render the template through mustache engine
        $template = $this->mustache->render($template, [
            'name' => $name, 
            'namespace' => config('h3pha3stus.filters.namespace'),
        ]);

        // Create directory if it does not exist
        if (!$this->file->exists(base_path($path))) {
            $this->file->makeDirectory(base_path($path));
        }

        // Save the file
        $this->file->put(base_path($path."/".$name.".php"), $template);

        $this->info('Filter has been generated!');
    }
}