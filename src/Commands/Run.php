<?php
namespace PhpAgent\Commands;

use phpagent\Agent;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Run extends Command {

    protected function configure()
    {
        $this
            ->setName('run')
            ->setDescription('Execute the agent (You can add this process to supervisord or pm2)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {



        $agent = new Agent($input, $output);
        $agent->run();
    }

}