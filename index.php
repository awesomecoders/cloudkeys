<?php

require_once 'lib/framework/dispatcher.php';

// BaseAutoLoader::register_base_lib('mysql');
// BaseAutoLoader::register_base_lib('couchdb');
BaseAutoLoader::register_base_lib('cloudcontrol');

if(file_exists('cloudkeys/settings_local.ini')) {
  $config = new ConfigIni('cloudkeys/settings.ini', 'cloudkeys/settings_local.ini');
} else {
  $config = new ConfigIni('cloudkeys/settings.ini', null);
}

$dispatcher = new Dispatcher(
    $config
  , 'cloudkeys'
);

$dispatcher->dispatch($_SERVER['REQUEST_URI']);
