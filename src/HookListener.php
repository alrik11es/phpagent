<?php
namespace PhpAgent;

class HookListener
{
    private $config;
    private $output;
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


    public function startup(){

        $app = function ($request, $response) {
            $response->writeHead(200, array('Content-Type' => 'text/plain'));
            $response->end("Hook launched\n");
        };

        $loop = \React\EventLoop\Factory::create();
        $socket = new \React\Socket\Server($loop);
        $http = new \React\Http\Server($socket, $loop);

        $http->on('request', $app);
//        echo "Server running at http://127.0.0.1:1337\n";

        $socket->listen($this->config->port);
        $loop->run();

    }

    /**
     * Executes plugins.
     * @param array $actions
     */
    public function execPlugins(array $actions)
    {
        if(count($actions)>0) {
            $this->output->writeln('<info>Executing plugins</info>');
            foreach ($actions as $action) {
                if ($this->execEvent($action)) {
                    $this->execAction($action);
                }
            }
        } else {
            $this->output->writeln('<error>No defined plugins</error>');
        }
    }

    /**
     * Executes plugins.
     * @param array $actions
     */
    public function execHooks(array $actions)
    {
        if(count($actions)>0) {
            $this->output->writeln('<info>Executing hooks</info>');
            foreach ($actions as $action) {
                if ($this->execEvent($action)) {
                    $result = $this->execAction($action);
                    if (property_exists($action, 'type') && $action->type == 'active') {
                        $this->execHook($action, self::HOOK_ACTIVE);
                    } else {
                        $this->execHook($action, self::HOOK_PASSIVE);
                    }
                }
            }
        } else {
            $this->output->writeln('<error>No defined hooks</error>');
        }
    }


}