<?php
namespace PhpAgent;

use PhpAgent\Plugins\IPlugin;
use React\Http\Response;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HookListener
{

    private $config;
    /** @var OutputInterface */
    private $output;
    /** @var InputInterface */
    private $input;

    /**
     * HookListener constructor.
     * @param $config
     * @param $output
     * @param $input
     */
    public function __construct($config, $output, $input)
    {
        $this->config = $config;
        $this->output = $output;
        $this->input = $input;
    }


    public function startup()
    {
        $loop = \React\EventLoop\Factory::create();
        $socket = new \React\Socket\Server($loop);
        $http = new \React\Http\Server($socket, $loop);

        $http->on('request', function (\React\Http\Request $request, Response $response) use($loop) {

            $response->writeHead(200, array('Content-Type' => 'text/plain'));
            $hook_executed = false;
            foreach($this->config->hooks as $hook){
                if($request->getPath() == $hook->url){
                    $hook_executed = true;
                    $this->output->writeln('Executing hook ... <info>'.$hook->name.'</info>');

                    $process = new \React\ChildProcess\Process($hook->params);
                    $process->start($loop);

                    $process->stdout->on('data', function ($chunk) {
                        echo $chunk;
                    });

                    $process->stdout->on('end', function () use ($response) {
                        $response->end("Hook launched\n");
                    });

                }
            }

            if(!$hook_executed){
                $response->end("No hook\n");
            }
        });

        $socket->listen($this->config->port);
        $loop->run();

    }
}