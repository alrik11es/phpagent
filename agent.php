<?php
define('AGENT_PATH', realpath(dirname(__FILE__)));
include('vendor/autoload.php');

$app = new \Symfony\Component\Console\Application();
$app->setName('PHPAgent');

//$app->setConfig($config);
//$app->add(new \Commands\Config());
$app->add(new \Commands\Daemon());
$app->run();