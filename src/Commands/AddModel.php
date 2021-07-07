<?php

namespace Lettrue\Genrate\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;

class AddModel extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'lettrue:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Eloquent model class';

    /**
     * 生成类的类型
     *
     * @var string
     */
    protected $type = 'Model';

    protected function getStub()
    {
        return __DIR__.'/Stubs/model.stub';
    }

    /**
     * 获取类的默认命名空间
     *
     * @param string $rootNamespace
     * @return string
     */

    protected function getDefaultNamespace($rootNamespace)
    {
        return is_dir(app_path('Models')) ? $rootNamespace.'\\Models' : $rootNamespace;
    }
}
