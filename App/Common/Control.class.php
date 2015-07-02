<?php
namespace App\Common;

class Control
{
    public $values;
    public $model;
    public $config;
    /*
     * init the values from post_array，then instance the model
     */
    public function __construct(){
        global $postValues,$config;
        $this->values = $postValues;
        $classPath = '\\App\\'. $this->values['module'] .'\\Model';
        $configFile = BASEDIR.SEPARATOR . "App" . SEPARATOR.ucfirst($this->values['module']).SEPARATOR."config.php";
        if(file_exists($configFile)){
            include_once $configFile;
            $this->config = $config->{$this->values['module']};
        }
        $this->model = new $classPath;
    }
    
    
    /*
     * 通过账号和token验证用户合法性
     */
    public function checkToken($uid){
        $value['uid'] = $uid;
        if(!empty($this->values['token']))
            $value['token'] = $this->values['token'];
        else
            $value['token'] = '';
        $value['time'] = time();
        $row = $this->model->checkToken($value);
        if(!$row)response(400,'请登录');
    
    }

}

?>