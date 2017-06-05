<?php
namespace PhpAgent;

class Application
{
    public function run()
    {
        $app = new \Symfony\Component\Console\Application();
        $app->setName('PHPAgent');

        //$app->setConfig($config);
        //$app->add(new \Commands\Config());
        $app->add(new \PhpAgent\Commands\Run());
        $app->run();
    }
}