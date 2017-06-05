<?php
namespace PhpAgent\Plugins;

class Now extends AbstractPlugin implements IPlugin {

    /**
     * Executes the plugin and returns a response if needed in object format. (stdClass or whatever)
     * @return object
     */
    public function run($params)
    {
        return true;
    }

    /**
     * Returns a constant that specifies the type of plugin you're creating
     * @return int
     */
    public function type()
    {
        return self::EVENT;
    }
}