<?php
include('vendor/autoload.php');

$app = new \Symfony\Component\Console\Application();
$app->setName('PHPAgent');

//$app->setConfig($config);
//$app->add(new \Commands\Config());
$app->add(new \phpagent\Commands\Daemon());
$app->add(new \phpagent\Commands\Run());
$app->run();