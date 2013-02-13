<?php
return array (
  'database' => 
  array (
    'host' => '192.168.100.241',
    'dbname' => 'cc5tudio_flexdds',
    'username' => 'cc5tudio_flexdds',
    'password' => '23wesdxc@#WESDXC',
    'adapter' => 'pdo_mysql',
    'tableprefix' => 'tine20_',
    'port' => '',
  ),
  'setupuser' => 
  array (
    'username' => 'tine20setup',
    'password' => 'a0f848942ce863cf53c0fa6cc684007d',
  ),
  'rest' =>
  array (
    'url' => 'http://localhost/staging/administrator/components/com_civicrm/civicrm/extern/rest.php',
    'useCustomCredentials' => true,
    'user' => 'scheduler_client',
    'password' => '23wesdxc@#WESDXC',
  ),
  'logger' => 
  array (
    'active' => true,
    'filename' => '/tmp/tine20.log',
    'priority' => 7,
  ),
  'caching' => 
  array (
    'active' => false,
    'lifetime' => '',
  ),
  'tmpdir' => '/tmp',
  'session' => 
  array (
    'path' => '',
    'lifetime' => 86400,
  ),
  'filesdir' => '',
  'mapPanel' => 1,
);
