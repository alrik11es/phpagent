<?php
namespace phpagent;

/**
 * Class Agent
 * Load config if not load default
 * Run actions if
 *
 */
class Agent {

    public function run()
    {
        $this->loadConfigIfExists();
    }

    public function loadConfigIfExists()
    {
        $dir = scandir(EXEC_PATH);
        array_shift($dir);
        array_shift($dir);
        foreach($dir as $file){
            echo $file."\n";
        }

    }
}