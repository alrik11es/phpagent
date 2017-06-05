<?php

namespace PhpAgent\Plugins;


use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractPlugin implements IPlugin {

    /** @var InputInterface */
    public $input;
    /** @var OutputInterface */
    public $output;

}