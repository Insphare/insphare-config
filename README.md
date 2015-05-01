Configuration - Management
===========

​The simple manager allows you to manage your configuration very easily. Also environment levels are supported.
If you want, you are able to add your own environment names. You only have to setup your directories and which environment are currently used.

The configuration manager depends on yaml parser. The config notation is yaml. The yaml notation is human readable and easy to understand.
One of the feature is the environment level support. You are able to customize your own config and get the values by environment level.
You can also add directories that overwrite the config values. There can be versioned but it is not required. For example for secret passwords.
For an even easier use, you can add an identifier to mark up sub folders who contains overwrite config values.

Get it started
===========

```php

// setup in your bootstrap.php
use Insphare\Base\ObjectContainer;
use Insphare\Config\Configuration;

// get the object
$container = new ObjectContainer();
$configuration = $container->getConfiguration();

// set your current environment
$configuration->setStaging(Configuration::ENVIRONMENT_STABLE);

// set the destination to your configuration folder
$configuration->addConfigPath(dirname(__DIR__) . '/config');

// set the destination to your overwrite-configuration folder (secret folder for example)
$configuration->addConfigPath(dirname(__DIR__) . '/secretConfigFolder');

// use filename as index key
$configuration->setUseFileNameAsKey(true);
```
