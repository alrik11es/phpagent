<?php
namespace phpagent;

use phpagent\Plugins\IPlugin;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Agent
 * Load config if not load default
 * Run actions if
 *
 */
class Agent {

    public $extract_methods = array('plugins', 'actions', 'hooks');
    /** @var InputInterface */
    private $input;
    /** @var OutputInterface */
    private $output;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * Execute the agent
     */
    public function run()
    {
        $config_files = $this->loadJsonConfigFiles();
        $config = $this->getAgentConfig($config_files);
        $this->execPlugins($config->actions);
        $this->execPlugins($config->hooks);
    }

    /**
     * Executes plugins.
     * @param array $actions
     */
    public function execPlugins(array $actions)
    {
        foreach($actions as $action){
            if($this->execEvent($action)){
                $this->execAction($action);
            }
        }
    }

    /**
     * Obtain specified config and merge all files config with each other's
     * @param array $config_files
     * @return \stdClass
     */
    public function getAgentConfig(array $config_files)
    {
        $config = new \stdClass();
        foreach($this->extract_methods as $method){
            $config->$method = array();
        }
        foreach($config_files as $file){
            $json = json_decode(file_get_contents($file));
            if($json !== false){
                foreach($this->extract_methods as $method){
                    if(property_exists($json, $method) && is_array($json->$method)){
                        $config->$method = array_merge($config->$method, $json->$method);
                    }
                }
            }
        }
        return $config;
    }

    /**
     * Obtain the json files from the script working directory
     * @return array The array of config files with full path each
     */
    public function loadJsonConfigFiles()
    {
        $config_files = array();
        $dir = scandir(EXEC_PATH);
        if(count($dir) > 2) {
            array_shift($dir);
            array_shift($dir);
            foreach ($dir as $file) {
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                if ($ext == 'json') {
                    $config_files[] = EXEC_PATH . '/' . $file;
                }
            }
        }
        return $config_files;
    }

    /**
     * Executes single plugin based on action object
     * @param $action
     */
    private function execAction($action)
    {
        $class = $this->loadPlugin($action->action);
        if ($class) {
            $class->input = $this->input;
            $class->output = $this->output;
            $class->run($action->params);
        } else {
            $this->output->writeln('<error>Action plugin ' . $action->action . ' not found</error>');
        }
    }

    /**
     * Executes an event based on action object
     * @param $action
     * @return int|object
     */
    private function execEvent($action)
    {
        $result = 0;
        $class = $this->loadPlugin($action->event);
        if ($class) {
            $result = $class->run($action->params);
        } else {
            $this->output->writeln('<error>Event plugin ' . $action->event . ' not found</error>');
        }
        return $result;
    }

    /**
     * Loads a plugin based a on a name
     * @param $action
     * @return IPlugin
     */
    private function loadPlugin($plugin_name)
    {
        $result = false;
        $class_name = '\phpagent\Plugins\\' . $plugin_name;
        if (class_exists($class_name)) {
            /** @var IPlugin $class */
            $result = new $class_name();
        }
        return $result;
    }
}