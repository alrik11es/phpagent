<?php
namespace phpagent;

/**
 * Class Agent
 * Load config if not load default
 * Run actions if
 *
 */
class Agent {

    public $extract_methods = array('plugins', 'actions', 'hooks');

    public function run()
    {
        $config_files = $this->loadJsonConfigFiles();
        $config = $this->getAgentConfig($config_files);
        print_r($config);
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
}