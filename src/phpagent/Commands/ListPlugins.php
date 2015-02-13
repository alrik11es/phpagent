<?php
namespace phpagent\Commands;

use Process\IProcess;
use Process\Update;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Daemon extends Command {

    protected function configure()
    {
        $this
            ->setName('daemon')
            ->setDescription('Execute the program in idle mode (Remember to add it to crontab)')
            ->addArgument(
                'config',
                InputArgument::REQUIRED,
                'Config file'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = array(
            'REMOTE_ADDR' => 'application API URI',
            'PRIVATE_KEY' => 'xxxx',
            'PUBLIC_KEY' => 'xxxx'
        );

        $name = $input->getArgument('config');
        if ($name) {
            $text = 'Hello '.$name;
        } else {
            $text = 'Hello';
        }

        echo AGENT_PATH;
        shell_exec('cd '.AGENT_PATH.'; git pull; composer install;');

//        $processes = [
//            new Update()
//        ];
//        /** @var IProcess $process */
//        foreach($processes as $process){
//            $process->config();
//            $process->run();
//        }
//        if ($input->getOption('yell')) {
//            $text = strtoupper($text);
//        }


    }


}