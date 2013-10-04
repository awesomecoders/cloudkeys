<?php

require_once 'lib/framework/dispatcher.php';

// BaseAutoLoader::register_base_lib('mysql');
// BaseAutoLoader::register_base_lib('couchdb');
// BaseAutoLoader::register_base_lib('cloudcontrol');

$dispatcher = new Dispatcher(
    new ConfigIni('cloudkeys/settings.ini', null)
  , 'cloudkeys'
);
$dispatcher->dispatch($_SERVER['REQUEST_URI']);
