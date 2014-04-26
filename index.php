<?php

require_once 'lib/framework/dispatcher.php';

// BaseAutoLoader::register_base_lib('mysql');
// BaseAutoLoader::register_base_lib('couchdb');
BaseAutoLoader::register_base_lib('cloudcontrol');
BaseAutoLoader::register_base_lib('heroku');
BaseAutoLoader::register_library_path(dirname(__FILE__) . '/lib/s3');

if(file_exists('cloudkeys/settings_local.ini')) {
  $config = new ConfigIni('cloudkeys/settings.ini', 'cloudkeys/settings_local.ini');
} else {
  $config = new ConfigIni('cloudkeys/settings.ini', null);
}

// Wrap config store to enable reading Heroku ENV vars
$config = new HerokuConfigStore($config);

S3::setAuth($config->get('AWSAccessKey'), $config->get('AWSSecretKey'));
S3::setExceptions(true);

$dispatcher = new Dispatcher(
    $config
  , 'cloudkeys'
);

$dispatcher->dispatch($_SERVER['REQUEST_URI']);
