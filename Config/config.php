<?php 
$config = new stdClass();
//param for databases
$config->dbArray = array(
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'database' => 'mosaic_db'
 );
 
//the code of different status
$config->statusCode = array(
    'routeErr' => 402,
    'dbErr' => 401,
    'loginErr' => 403,
    'success' => 200
);