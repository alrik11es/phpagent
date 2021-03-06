# PHP Agent
A PHP agent designed to listen for hooks and trigger actions on the server. 

    This program is a concept and still under development.
    Please do not use it until first RC.

[![asciicast](https://asciinema.org/a/9n3qy9nwtefcz3u768on6cr9o.png)](https://asciinema.org/a/9n3qy9nwtefcz3u768on6cr9o)

## Requirements

* PHP 7.0+

## Installation PHAR build

When the releases start to come we will build phar files in order to ease the process of
installing.

    # curl -sS https://getcomposer.org/installer | php
    # mv phpagent.phar /usr/local/bin/phpagent

Once you're finished you can now execute:
    
    $ phpagent

## Installation from scratch

You're gonna need composer for this one.

    # curl -sS https://getcomposer.org/installer | php
    # mv composer.phar /usr/local/bin/composer
    # git clone https://github.com/alrik11es/phpagent
    # composer install
    
Once you're finished all this commands you can now execute:

    $ ./bin/phpagent

## Configuration
### Daemon mode
You can use programs like [supervisord](http://supervisord.org/), [pm2](http://pm2.keymetrics.io/) or [forever](https://github.com/foreverjs/forever) to let this program run in daemon mode.

### Configuration files
You can setup as many config files as you like. Supported config languages are JSON and YAML.
There is an execution order based on directory location.

* Default plugin config.
* `/etc/phpagent` config.

Be advise that if a config file writes a value of the config. Others can substitute but if empty value found there will be skipped 
## Operations

### Hooks
A hook is a specialized type of action that enables a React HTTP server to listen for specific requests. Like POST, GET or whatever. When the hook is fired then the action is performed.

```json
{
  "hooks": [{
      "name": "example-copy-hook",
      "route": "/api/hook",
      "action": "shell",
      "params": "cp /usr/local/file.txt /usr/local/file2.txt"
  }]
}
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Marcos Sigueros Fernández](https://github.com/alrik11es)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.