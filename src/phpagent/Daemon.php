<?php
namespace phpagent;

class Daemon {

    const DELAY = 1000000;
    private $stopServer = false;
    private $childProcesses = [];
    private $processName = 'Daemon';
    private $callbacks;
    private $group;
    private $user;

    public function __construct($callbacks = array())
    {
        $this->callbacks = $callbacks;
    }

    public function init()
    {
        $this->setGroupAndUser();
        if ($this->isDaemonActive()) {
            echo 'The Daemon is running already!' . "\n";
            exit;
        }
        $this->startDaemon();
        while (!$this->stopServer) {
            if (count($this->childProcesses) < count($this->callbacks)) {
                $this->startChildProcess();
            }
            usleep(self::DELAY);
            while ($signaled_pid = pcntl_waitpid(-1, $status, WNOHANG)) {
                if ($signaled_pid == -1) {
                    $this->childProcesses = [];
                    break;
                } else {
                    $signaled_callback = array_search($signaled_pid, $this->childProcesses);
                    unset($this->childProcesses[$signaled_callback]);
                }
            }
        }
        declare (ticks = 1);
    }

    public function isDaemonActive()
    {
        $pid_file = $this->getPidFile();
        if (is_file($pid_file)) {
            $pid = file_get_contents($pid_file);
            if (posix_kill($pid, 0)) {
                return true;
            } else {
                if (!unlink($pid_file)) {
                    exit(-1);
                }
            }
        }
        return false;
    }

    private function getNotRunningCallback()
    {
        foreach ($this->callbacks as $callback => $value) {
            if (!isset($this->childProcesses[$callback])) {
                return $callback;
            }
        }
    }

    private function getPidFile()
    {
        return '/var/run/php_' . $this->processName . '.pid';
    }

    private function startDaemon()
    {
        if ($pid = pcntl_fork()) {
            exit;
        }
        if (function_exists('cli_set_process_title')) {
            \cli_set_process_title('php-' . $this->processName . ': master process');
        }
        posix_setsid();
        file_put_contents($this->getPidFile(), getmypid());
    }

    private function startChildProcess()
    {
        $pid = pcntl_fork();
        $callback = $this->getNotRunningCallback();
        if ($pid == -1) {} elseif ($pid) {
            $this->childProcesses[$callback] = $pid;
        } else {
            posix_setuid($this->user);
            posix_setgid($this->group);
            $pid = getmypid();
            if (function_exists('cli_set_process_title')) {
                \cli_set_process_title('php-' . $this->processName . ': ' . $callback);
            }
            $this->callbacks[$callback]();
            sleep(10);
            exit;
        }
    }

    public function setGroupAndUser()
    {
        $this->group = posix_getgrnam('www-data')['gid'];
        $this->user  = posix_getpwnam('www-data')['uid'];
    }
}