<?php
namespace phpagent\Process;


interface IPlugin {

    const EVENT = 1;
    const ACTION = 2;

    /**
     * Executes the plugin and returns a response if needed in object format. (stdClass or whatever)
     * @return object
     */
    public function run($params);

    /**
     * Returns a constant that specifies the type of plugin you're creating
     * @return int
     */
    public function type();

}