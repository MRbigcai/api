<?php
namespace App\Register;

class Model extends \App\Common\Model
{
    /*
     * register account
     * param array fields of table
     * return int
     */
    public function register($valArr){
        $row = $this->db->insert('def_user',$valArr)->exec();       
        if($row)return $this->db->lastInsertId(); 
        return '';
    }

    /*
     * login for method
     * param string phone,string pwd
     */
    
    
    public function login($phone, $pwd, $expire){
        $result = $this->db->select('*')->from('def_user')->where("phone=". $phone . " and pwd='" .md5($pwd) . "'")->query()->fetch();
        if(!is_numeric($result['uid']) || empty($result['uid'])){
            response(403,'用户名或密码错误');
        }
        else{
            $data = array();
           //获取用户token
            $tmpToken = md5(time().$result['name']);
            $value['token'] = md5($tmpToken);
            $value['uid'] = $result['uid'];
            $value['time'] = time();
            $value['expire'] = $value['time']+$expire;
            
            //保存token
           $row = $this->saveToken($value);
           if($row) $data['token'] =  $tmpToken;
            
            //返回数据
            
           
            $data['uid'] = $result['uid'];
            $data['name'] = $result['name'];
            $data['mail'] = $result['mail'];
            $data['birthday'] = $result['birthday'];
            $data['signature'] = $result['signature'];
            $data['province'] = $result['province'];
            $data['city'] = $result['city'];
            $data['county'] = $result['county'];
            $data['sex'] = $result['sex'];
            $data['icon'] = $result['icon'];
            $data['ryToken'] = $result['ryToken'];
           
            
//            $data['token'] = $postValues['token'];
            response(200,'success',$data);
            
        }
        
    }
    
    /*
     * save the token from rongcloud
     * param string token
     * param int userId
     * return bool
     */
    public function saveToken($tokenArr){
       return $this->db->insert('def_user_token', $tokenArr)->duplicate($tokenArr)->exec();
    }
    
    /*
     * save ryToken
     */
    public function saveRyToken($tokenArr,$lastId){
        return $this->db->update('def_user', $tokenArr)->where('uid=' .$lastId)->exec();
    }
}

?>