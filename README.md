## PHP Agent
A PHP agent designed to fulfill your needs on any remote server. It uses an API to contact outside server app.

##Installation PHAR build

When the releases start to come we will build phar files for you in order to ease the process of
installing without the need of composer.

    # apt-get install git php5 php5-curl
    # curl -sS https://getcomposer.org/installer | php
    # mv phpagent.phar /usr/local/bin/phpagent

Once you're finished all this commands you can now execute:
    $ phpagent
    

##Installation from scratch

You're gonna need composer for this one. And PHP 5 installed.

    # apt-get install git php5 php5-curl
    # curl -sS https://getcomposer.org/installer | php
    # mv composer.phar /usr/local/bin/composer
    # git clone https://github.com/alrik11es/agent.git
    # composer install
    
Once you're finished all this commands you can now execute:

    $ ./bin/agent

## Daemon
This app has a daemon ready to go. There are two ways to use this daemon as a real daemon or as a cronjob
depending on your needs.

## Events
An event is a moment on the daemon life where you want to execute some action or hook

- startup
- first-time
- now

## Actions
In PHP agent the actions are when you want to execute some commands in the daemon mode usually when an event occurs. 

```json
{
  "name": "example-copy-action",
  "event": "startup",
  "action": "shell",
  "params": "cp /usr/local/file.txt /usr/local/file2.txt"
}
```

## Hooks
A hook is a specialized type of action that sends the action result to a web-service on a server and allows specifying
 whether the hook is passive or active the possibility that this server returns some kind of feedback to the agent.

```json
{
  "name": "example-copy-hook",
  "type": "active",
  "url": "http://myserver.com/api/hook",
  "event": "startup",
  "action": "shell",
  "params": "cp /usr/local/file.txt /usr/local/file2.txt"
}
```
        
## Plugins
We have a lot of plugins and growing, each plugin could be an event or action.

### Creating your own plugins
As this cannot be really useful unless you can deploy new plugins to work with your agents we have created a way you
 can create the plugins with the most simple way possible.
