<?php 
$config = new stdClass();
//param for databases
$config->dbArray = array(
    'host' => 'localhost',
    'user' => 'cjb',
    'password' => '10086',
    'database' => 'mosaic_db'
 );
 
//the code of different status
$config->statusCode = array(
    'routeErr' => 402,
    'dbErr' => 401,
    'loginErr' => 403,
    'success' => 200
);

$config->memArray = array(
    'host' => '192.168.128.128',
    'poort' => 11211
);
