<?php
namespace PhpAgent;

class HookListener
{
    public function startup(){

        $app = function ($request, $response) {
            $response->writeHead(200, array('Content-Type' => 'text/plain'));
            $response->end("Hook launched\n");
        };

        $loop = \React\EventLoop\Factory::create();
        $socket = new \React\Socket\Server($loop);
        $http = new \React\Http\Server($socket, $loop);

        $http->on('request', $app);
        echo "Server running at http://127.0.0.1:1337\n";

        $socket->listen(1337);
        $loop->run();

    }

}