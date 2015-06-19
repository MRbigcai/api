<?php
//目录分隔符
define("SEPARATOR", DIRECTORY_SEPARATOR);
//系统根目录
define("BASEDIR", __DIR__);
//记录接收到的数据到本地文件
$tmpPost = $_POST;
unset($tmpPost['picture']);
file_put_contents(BASEDIR . SEPARATOR . "logs" .SEPARATOR . "mes.txt", date("l dS \\of F Y h:i:s A") . "  " . JSON_ENCODE($tmpPost) . "\r\n", FILE_APPEND);
unset($tmpPost);
//引入自定义库文件和全局配置文件
include_once BASEDIR. SEPARATOR. "Common". SEPARATOR. "Functions.php";
include_once BASEDIR. SEPARATOR. "Config". SEPARATOR. "config.php";
//类自动加载
$db = \Lib\DbControl::getInstance();
$db->connect($config->dbArray['host'], $config->dbArray['user'], $config->dbArray['password'], $config->dbArray['database']);

$a = $db->querySql('select * from def_blog');
echo json_encode($a);
?>