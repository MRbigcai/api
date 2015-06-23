<?php
namespace App\Personal;

class Control extends \App\Common\Control
{
    
    public function home(){
    
    }
    
    
    /*
     * see who the user follow
     * param int otherId,the other user`s userId
     * param int myId,my userId;
     */
    public function seeOtherUserFollowing(){
        if(empty($this->values['theOtherId'])||empty($this->values['myId'])){
            response('404','用户id为空');
        }
        $myId = $this->values['myId'];
        $theOtherId = $this->values['theOtherId'];
        $data = array();
        $data['common'] = $this->model->getFollowingInCommon($myId, $theOtherId);
        $page = isset($this->values['page'])?$this->values['page']:1;
        $pageSize = 10;
        $message = $this->model->getFollowingMessage($myId, $theOtherId, $page, $pageSize);
        $data['following'] = $message['followingMessage'];
        $data['followingCount'] =  $message['followingCount'];
        response(200,'success',$data);
    
    }
    
    /*
     * personal home page message
     * 
     * 
     */
    public function personalHomePage(){
        
        if(!is_numeric($this->values['uid']))response(403,'用户id不合法');
        $page = isset($this->values['page'])?$this->values['page']:1;
        $pageSize = 10;
        $fieldString = "name,icon,uid,sex,blogCount,followingCount,followerCount";
        $uid = $this->values['uid'];
        $data['userMess'] = $this->model->getUserMessage($fieldString, $uid);
        $fieldString = "`id`,`content`,`pic`,`comment`,`likes`,`pic_high`,`pic_width`,`longtitude`,`latitude`";
        $data['blogMess'] = $this->model->getblogByUid($fieldString, $uid, $page, $pageSize);
 //       print_r($data);exit;
        response(200,'success',$data);
        
    }
    
    /*
     * add following
     */
    public function addFollowing(){
        if (empty($this->values['myId']) || empty($this->values['theOtherId']))response(400,'用户id不存在');
        $value['fromUid'] = $this->values['myId'];
        $value['followingUid'] = $this->values['theOtherId'];
        $value['addTime'] = time();
        $row = $this->model->addFollowing($value);
        if($row)response(200,'success');
        
    }
    
    
    /*
     * remove following
     */
    public function removeFollowing(){
        if (empty($this->values['myId']) || empty($this->values['theOtherId']))response(400,'用户id不存在');
        $value['fromUid'] = $this->values['myId'];
        $value['followingUid'] = $this->values['theOtherId'];
        $value['addTime'] = time();
        $row = $this->model->removeFollowing($value);
        if($row)response(200,'success');
    
    }
    
    /*
     * favorite the blog
     * 
     */
    public function addFavorite(){
        if (empty($this->values['myId']) || empty($this->values['bid']))response(400,'用户或者博文不存在');
        $value['uid'] = $this->values['myId'];
        $value['bid'] = $this->values['bid'];
        $value['addTime'] = time();
        $row = $this->model->addFavorite($value);
        if($row)response(200,'success');
        return '';
        
    }
    
    /*
     * reset pwd by phone
     * post value:phone 
     */
    public function checkIfRegisterByPhone(){
        $phone = !empty($this->values['phone'])?$this->values['phone']:'';
        $result = $this->model->getUserMessageByPhone('uid', $phone);
        if(!$result)response(400,'用户不存在');
        response(200,'success');
        
    }
    
    
    /*
     * reset pwd 
     * post value:pwd ckpwd
     */
    public function resetPwd(){
//      $checkCode = $this->values['checkCode'];        
        $pwd = $this->values['pwd'];
        $ckpwd = $this->values['ckpwd'];
        $phone = $this->values['phone'];
        if($pwd != $ckpwd)response(400,'密码不一致');
        $values = array();
        $values['pwd'] = md5($pwd);
        $row = $this->model->resetPwd($values, $phone);
        if($row || $row === 0)response(200,'success');
        response(400,'密码和原密码一致');
        
        
    }
    
    
    /*
     * change the face
     * post value:uid,pic
     */
    public function changeFace(){
        if(!is_numeric($this->values['uid']))response(400,'用户id不正确');
        $name = \Lib\PhotoUtility::savePhotoWithClientData(BASEDIR. SEPARATOR .'Resources' . SEPARATOR . 'userIcon' . SEPARATOR,$this->values['icon']);
        $values['icon'] = "http://" . $_SERVER['HTTP_HOST'] . "/Resources/userIcon/" . $name;
        $row = $this->model->changeUserData($values, $this->values['uid']);
        if($row)response(200,'success');
        return '';
   
    }
    
    
    /*
     * change the user data
     * 
     */
    public function changeUserData(){
        $uid = $this->values['uid'];
        $type = $this->values['type'];
        if($uid){
            $values = array();
            if($type == 'position'){
                $values['province'] = $this->values['province'];
                $values['city'] = $this->values['city'];
                $values['county'] = $this->values['county'];
            }else{ 
                $values[$type] = $this->values[$type];
            }
/*            $values['name'] =  $this->values['name'];
            $values['sex'] =  $this->values['sex'];
            $values['mail'] =  $this->values['mail'];
            $values['birthday'] =  $this->values['birthday'];
            $values['signature'] =  $this->values['signature'];
            $values['province'] =  $this->values['province'];
            $values['city'] =  $this->values['city'];*/
            $row = $this->model->changeUserData($values, $uid);
            if($row || $row === 0)response(200,'success');
            response(400,'未修改');
        }
        return response(400,'检查uid是否存在');
        
        
        
    }

}

?>