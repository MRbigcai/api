<?php
$config->register = new stdClass();
$config->register->insertField = array('phone', 'pwd', 'sex', 'name');
$config->register->rongCloud = array(
    'appKey' => 'c9kqb3rdkhwfj',
    'appSecret' => 'P1duRI5hacX',
    'host' => 'api.cn.rong.io',
    'path' => '/user/getToken.json'
    
);
$config->register->tokenExpire = 86400;