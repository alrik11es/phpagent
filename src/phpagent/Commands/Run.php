<?php
namespace phpagent\Commands;

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
            ->setDescription('Execute the agent once (You can add this to crontab)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $agent = new Agent($input, $output);
        $agent->run();

    }


}