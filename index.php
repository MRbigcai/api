<?php
//目录分隔符
define("SEPARATOR", DIRECTORY_SEPARATOR);
//系统根目录
define("BASEDIR", __DIR__);
//记录接收到的数据到本地文件
$tmpPost = $_POST;
unset($tmpPost['pic']);
file_put_contents(BASEDIR . SEPARATOR . "logs" .SEPARATOR . "mes.txt", date("l dS \\of F Y h:i:s A") . "  " . JSON_ENCODE($tmpPost) . "\r\n", FILE_APPEND);
unset($tmpPost);
//引入自定义库文件和全局配置文件
include_once BASEDIR. SEPARATOR. "Common". SEPARATOR. "Functions.php";
include_once BASEDIR. SEPARATOR. "Config". SEPARATOR. "config.php";
//类自动加载
$db = \Lib\DbControl::getInstance();
$db->connect($config->dbArray['host'], $config->dbArray['user'], $config->dbArray['password'], $config->dbArray['database']);
//读取请求的模块和方法
if(empty($_POST['method']) || empty($_POST['module'])){
    file_put_contents(BASEDIR . SEPARATOR . "logs" .SEPARATOR . "mes.txt", date("l dS \\of F Y h:i:s A") . " 请检查调用的模块或者方法是否正确,key是否大小写 method:" . $_POST['method']." module: ".$_POST['module'] . "\r\n", FILE_APPEND);
    response(402,'请检查调用的模块或者方法是否正确,key是否大小写');
}

$postValues = $_POST;
unset($_POST);
$module = ucfirst(strtolower($postValues['module']));
$method = $postValues['method'];
//开启session
/*$sess = new Lib\Session;
session_start();*/
//启动路由
Lib\Route::index($module, $method);
