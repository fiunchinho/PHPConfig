PHPConfig
==========

PHPConfig is a simple library that will let you handle different configuration settings for different environments pretty easily. Instead of having strange yaml files that are hard to read, you can just use plain PHP (feel free to change yaml with json or xml in the last sentence).

This project is under development. Use it at your own risk.


How does it work
----------
The config class needs an instance of a file handler to include the file containing the configuration array. You can use/write your own file handler. It just needs to require the file with the config array. Then, you just need to get an instance of the config class passing the environment where you are, along with the file handler. And that's it! You get the config values like:

```php
<?php

use PHPConfig\Config;

$fileHandler	= new File( 'my_config.php' );
$config			= new Config( 'development', $file );
$config->get( 'username' );
$config->get( 'password' );
$config->get( 'db_host', 'default_host' );
```

Config values inheritance
---------------------------
You can get inheritance accross different environments. If you equalize to environments, the second one will inherit from the first one. Afterwards you may set new values for that environment, or even overwrite the existing ones. The inheritance accepts several levels.
There is a example config file.

Testing some stuff here.
