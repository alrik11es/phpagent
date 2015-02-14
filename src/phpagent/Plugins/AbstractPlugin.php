<?php

namespace phpagent\Plugins;


use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractPlugin implements IPlugin {

    /** @var InputInterface */
    protected $input;
    /** @var OutputInterface */
    protected $output;

}