<?php
$config->Register = new stdClass();
$config->Register->insertField = array('phone', 'pwd', 'sex', 'name');
$config->Register->rongCloud = array(
    'appKey' => 'c9kqb3rdkhwfj',
    'appSecret' => 'P1duRI5hacX',
    'host' => 'api.cn.rong.io',
    'path' => '/user/getToken.json'
    
);
$config->Register->tokenExpire = 86400;