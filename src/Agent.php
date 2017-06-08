<?php
namespace PhpAgent;

use Cowsayphp\Cow;
use Dflydev\DotAccessData\Data;
use phpagent\Plugins\IPlugin;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Agent
 * Load config if not load default
 * Run actions if
 *
 */
class Agent {

    /** @var InputInterface */
    private $input;
    /** @var OutputInterface */
    private $output;

    private $config = null;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $this->config = new Config();
    }

    /**
     * Execute the agent
     */
    public function run()
    {
        $this->output->writeln('<bg=green;options=bold>Starting PHPAgent...</>');
//        $config_files = $this->loadJsonConfigFiles();
//        $config = $this->getAgentConfig($config_files);
//        $this->execPlugins($config->actions);
//        $this->execHooks($config->hooks);

        $this->loadConfig();
        if($this->output->isVerbose()) {
            $this->output->writeln('Config is now loaded');
        }

        $this->output->writeln('Starting reactor listener ... <bg=blue;options=bold>Idle</>');

        $hookListener = new HookListener($this->config, $this->output, $this->input);
        $hookListener->startup();

    }

    private function loadConfig()
    {
        // load config from directories
        $finder = new Finder();
        $files = $finder->ignoreUnreadableDirs()->in([AGENT_PATH.'/../config', '/etc/phpagent'])->files()->name('/\.json$|\.yaml$/');

        if($this->output->isVerbose()) {
            $this->output->writeln('<info>Remember that the file priority is /etc/phpagent and then [...]/phpagent_install/config</info>');
            $this->output->writeln('<info>Detected ' . count($files) . ' config files</info>');
        }

        foreach($files as $file){
            $content = $file->getContents();
            if($this->isJson($content)){
                $config = json_decode($content, true);
                $this->parseConfig($config);
                if($this->output->isVerbose()) {
                    $this->output->writeln('Parsing ' . $file->getRelativePathname() . ' as JSON');
                }
            }

            try{
                $config = Yaml::parse($content);
                $this->parseConfig($config);
                if($this->output->isVerbose()) {
                    $this->output->writeln('Parsing ' . $file->getRelativePathname() . ' as YAML');
                }
            } catch (\Exception $e){

            }
        }

    }

    private function parseConfig($config)
    {
        $dnp = \Alr\ObjectDotNotation\Data::load($config);
        if(!empty($dnp->get('config.port'))) $this->config->port = $dnp->get('config.port');
        if(!empty($dnp->get('actions'))) $this->config->actions = $dnp->get('actions');

    }


    /**
     * Loads a plugin based a on a name
     * @param $action
     * @return IPlugin
     */
    private function loadPlugin($plugin_name)
    {
        $sanitized_name = $this->getSanitizedPluginName($plugin_name);
        $result = false;
        $class_name = '\phpagent\Plugins\\' . $sanitized_name;
        if (class_exists($class_name)) {
            /** @var IPlugin $result */
            $result = new $class_name();
            $type = ($result->type() == IPlugin::EVENT ? "Event" : "Action");

            $message = '<info>'.$type.' plugin loaded</info>';
            $message .= ' ... '.$sanitized_name;
            $result->input = $this->input;
            $result->output = $this->output;

            $this->output->writeln($message);
        } else {
            $this->output->writeln('<error>' . $sanitized_name . ' plugin not found</error>');
        }
        return $result;
    }

    /**
     * Returns the plugin sanitized name.
     * @param $plugin_name
     * @return string
     */
    private function getSanitizedPluginName($plugin_name)
    {
        $sanitized_name = ucfirst(strtolower($plugin_name));
        return $sanitized_name;
    }

    public function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

}