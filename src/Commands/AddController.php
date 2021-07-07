<?php

namespace Lettrue\Genrate\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;

class AddController extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'lettrue:controllor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create a new controller';

    /**
     * 生成类的类型
     *
     * @var string
     */
    protected $type = 'Controller';

    protected function getStub()
    {
        return __DIR__.'/Stubs/controller.stub';
    }

    /**
     * 获取类的默认命名空间
     *
     * @param string $rootNamespace
     * @return string
     */

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Controllers';
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->laravel['path'].'/'.str_replace('\\', '/', $name . 'Controller').'.php';
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in the base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $controllerNamespace = $this->getNamespace($name);

        $replace = [];

        if ($this->option('model')) {
            $this->createModel();
        }

        if ($this->option('request')) {
            $this->createRequest();
        }

        $replace["use {$controllerNamespace}\Controller;\n"] = '';

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * Build the model replacement values.
     *
     * @return array
     */
    protected function createModel()
    {
        $request = Str::studly(class_basename($this->argument('name')));
        $this->call('lettrue:model', array_filter(['name' => $request]));
    }


    /**
     * Build the request .
     *
     * @return void
     */
    protected function createRequest()
    {
        $request = Str::studly(class_basename($this->argument('name')));
        $this->call('lettrue:request', array_filter([
            'name'  => "{$request}Request",
        ]));
    }

    /**
     * Get the fully-qualified model class name.
     *
     * @param  string  $model
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function parseModel($model)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        return $this->qualifyModel($model);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_NONE, 'Generate a resource controller for the given model.'],
            ['request', 'r', InputOption::VALUE_NONE, 'Generate a request controller class.'],
        ];
    }
}
