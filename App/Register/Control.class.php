<?php
namespace App\Register;

class Control extends \App\Common\Control
{
    /*
     * register
     */
    public function register(){
        $this->values['name'] = isset($this->values['name'])?$this->values['name']:$this->getDefaultName();
        //remove the field not in table
        foreach ($this->config->insertField as $k => $v){
            $values[$v] = $this->values[$v]; 
        }
        $values['pwd'] = md5($values['pwd']);
        $lastId = $this->model->register($values);
        if($lastId){
            $rongCloudApi = new \Lib\ServerAPI($this->config->rongCloud['appKey'],$this->config->rongCloud['appSecret']);
            $rongCloudJson = $rongCloudApi->getToken($lastId, $values['name'], 'http%3A%2F%2Fabc.com%2Fmyportrait.jpg');//userId,userName,URI of photo
            $rongCloudArray = json_decode($rongCloudJson,true);
            $rongCloudToken = $rongCloudArray['token'];
            $rows = $this->model->saveToken(array('ryToken'=>$rongCloudToken), $lastId);
            if($rows)response(200,'success');
        }
        return '';
    }
    
    public function login(){
        $phone = $this->values['phone'];
        $pwd = $this->values['pwd'];
        $this->model->login($phone, $pwd);
    }
    
    
    /*
     * get a default userName
     * return string name;
     */
    public function getDefaultName(){
        $string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $len = strlen($string);
        $n = 0;
        $name = '';
        for ($n;$n<6;$n++){
            $start = rand(0,$len);
            $name .= substr($string,$start,1);
        }
        return $name;
        
    }
}

?>