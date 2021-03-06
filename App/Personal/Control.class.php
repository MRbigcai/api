<?php
namespace App\Personal;

class Control extends \App\Common\Control
{
    
    
    /*
     * see who the user follow
     * post value int otherId,the other user`s userId
     * post value int myId,my userId;
     */
    public function seeUserFollowing(){
  /*      if(empty($this->values['theOtherId'])||empty($this->values['myId'])){
            response('404','用户id为空');
        }*/
        //是他们关注列表还是自己关注列表me,other
        $page = isset($this->values['page'])?$this->values['page']:1;
        $pageSize = 10;
        $myId = isset($this->values['myId'])?$this->values['myId']:0;
        $theOtherId = isset($this->values['theOtherId'])?$this->values['theOtherId']:0;
        $data = array();
        if($this->values['who'] == 'other'){
            $data['common'] = $this->model->getFollowingInCommon($myId, $theOtherId);
            $message = $this->model->getFollowingMessage($myId, $theOtherId, $page, $pageSize);
            $data['following'] = $message['followingMessage'];
            $data['followingCount'] =  $message['followingCount'];
        }elseif ($this->values['who'] == 'me'){   
            $this->checkToken($myId);
            $message = $this->model->getFollowingMessage($myId, $myId, $page, $pageSize);
            $data['following'] = $message['followingMessage'];
            $data['followingCount'] =  $message['followingCount'];
        }else{
            response(400,'error who',array('error'=>'who'));
        }
        if(!empty($data))response(200,'success',$data);
        response(200,'success');
    
    }
    
    
    
    /*
     * 他人粉丝列表
     * post value int page,myId,theOtherId
     * post value string who
     */
    public function seeUserFollower(){
        $page = isset($this->values['page'])?$this->values['page']:1;
        $pageSize = 10;
/*        if(empty($this->values['theOtherId'])||empty($this->values['myId'])){
            response('404','用户id为空');
        }*/
        $myId = isset($this->values['myId'])?$this->values['myId']:0;
        $theOtherId = isset($this->values['theOtherId'])?$this->values['theOtherId']:0;
        if($this->values['who'] == 'other'){
            $message = $this->model->getFollowerMessage($myId, $theOtherId, $page, $pageSize);
            $data['following'] = $message['followerMessage'];
            $data['followingCount'] =  $message['followerCount'];
        }elseif ($this->values['who'] == 'me'){
            $this->checkToken($myId);
            $message = $this->model->getFollowerMessage($myId, $myId, $page, $pageSize);
            $data['following'] = $message['followerMessage'];
            $data['followingCount'] =  $message['followerCount'];
        }else{
            response(400,'error who',array('error'=>'who'));
        }
        if(!empty($data))response(200,'success',$data);
        response(200,'success');
    }
    
    
    
    /*
     * personal home page message
     * post value int page,myId,theOtherId
     * post value string who
     */
    public function personalHomePage(){
        $page = isset($this->values['page'])?$this->values['page']:1;
        $pageSize = 10;
        $userFieldString = "name,icon,uid,sex,blogCount,followingCount,followerCount,background";
        $myId = isset($this->values['myId'])?$this->values['myId']:0;
        $theOtherId = isset($this->values['theOtherId'])?$this->values['theOtherId']:0;
 //       $blogFieldString = "`id`,`content`,`pic`,`comment`,`likes`,`pic_high`,`pic_width`,`longtitude`,`latitude`";
        if($this->values['who'] == 'other'){
            $theOtherId = $this->values['theOtherId'];
            $data['userMess'] = $this->model->getUserMessageWithFollower($userFieldString, $theOtherId, $myId);
            $data['blogMess'] = $this->model->getblogByUid($theOtherId, $page, $pageSize);
        }elseif ($this->values['who'] == 'me'){
            $this->checkToken($myId);
            $data['userMess'] = $this->model->getUserMessage($userFieldString, $myId);
            $data['blogMess'] = $this->model->getblogByUid($myId, $page, $pageSize);
        }else{
            response(400,'请选择是个人主页还是他人主页',array('error'=>'who'));
        }

        if(!empty($data))response(200,'success',$data);
        response(200,'success');
        
    }
    
    /*
     * add following
     * post value int myId,theOtherId
     */
    public function addFollowing(){
        if (empty($this->values['myId']) || empty($this->values['theOtherId']))response(400,'用户id不存在');
        $myId = isset($this->values['myId'])?$this->values['myId']:0;
        $this->checkToken($myId);
        $value['fromUid'] = $this->values['myId'];
        $value['followingUid'] = $this->values['theOtherId'];
        $value['addTime'] = time();
        $row = $this->model->addFollowing($value);
        if($row)response(200,'success');
        response(400,'添加失败');
        
    }
    
    
    /*
     * remove following
     * post value int myId,theOtherId
     */
    public function removeFollowing(){
        if (empty($this->values['myId']) || empty($this->values['theOtherId']))response(400,'用户id不存在');
        $myId = isset($this->values['myId'])?$this->values['myId']:0;
        $this->checkToken($myId);
        $value['fromUid'] = $this->values['myId'];
        $value['followingUid'] = $this->values['theOtherId'];
        $value['addTime'] = time();
        $row = $this->model->removeFollowing($value);
        if($row)response(200,'success');
        response(400,'移除失败');
    
    }
    
    /*
     * favorite the blog
     * post value int myId,bid
     * 
     */
    public function addFavorite(){
        $myId = isset($this->values['myId'])?$this->values['myId']:0;
//        $theOtherId = isset($this->values['theOtherId'])?$this->values['theOtherId']:0;
        $this->checkToken($myId);
        $value['uid'] = $this->values['myId'];
        $value['bid'] = $this->values['bid'];
        $value['addTime'] = time();
        $row = $this->model->addFavorite($value);
        if($row)response(200,'success');
        response(400,'添加失败');
        
    }


/*
     * 删除收藏
     * post value int myId,bid
     * 
     */
    public function removeFavorite(){
        $myId = isset($this->values['myId'])?$this->values['myId']:0;
        $theOtherId = isset($this->values['theOtherId'])?$this->values['theOtherId']:0;
        $this->checkToken($myId);
        $value['uid'] = $this->values['myId'];
        $value['bid'] = $this->values['bid'];
        $value['addTime'] = time();
        $row = $this->model->removeFavorite($value);
        if($row)response(200,'success');
        response(400,'添加失败');
        
    }
     /*
     * 获取收藏列表
     * post value:bid
     * 
     */
    public function getFavoriteList(){
        $myId = isset($this->values['myId'])?$this->values['myId']:0;
        $this->checkToken($myId);
	    $data['result'] = $this->model->getFavoriteList($myId);
	    if(!empty($data))response(200,'success',$data);
        response(200,'success');
       
        
    }
 /*
     * 点赞博文
     * post value int myId,bid
     * 
     */
    public function addLike(){
        $myId = isset($this->values['myId'])?$this->values['myId']:0;
//        $theOtherId = isset($this->values['theOtherId'])?$this->values['theOtherId']:0;
        $this->checkToken($myId);
        $value['uid'] = $this->values['myId'];
        $value['bid'] = $this->values['bid'];
        $value['addTime'] = time();
        $row = $this->model->addLike($value);
        if($row)response(200,'success');
        response(400,'添加失败');
        
    }


/*
     * 取消点赞
     * post value int myId,bid
     * 
     */
    public function removeLike(){
        $myId = isset($this->values['myId'])?$this->values['myId']:0;
//        $theOtherId = isset($this->values['theOtherId'])?$this->values['theOtherId']:0;
        $this->checkToken($myId);
        $value['uid'] = $this->values['myId'];
        $value['bid'] = $this->values['bid'];
        $value['addTime'] = time();
        $row = $this->model->removeLike($value);
        if($row)response(200,'success');
        response(400,'添加失败');
        
    }
    /*
     * 获取博文下点赞用户
     * post value:bid
     *
     */
    public function getLikeUsers(){
        $bid = isset($this->values['bid'])?$this->values['bid']:0;
        $data['result'] = $this->model->getLikeUsers($bid);
        if(!empty($data))response(200,'success',$data);
        response(200,'success');
         
    
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
     * post value:pwd ckpwd myId
     */
    public function resetPwd(){
//      $checkCode = $this->values['checkCode']; 
        $myId = isset($this->values['myId'])?$this->values['myId']:0;
        $this->checkToken($myId);
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
     * post value:uid,icon
     */
    public function changeFace(){
        $uid = isset($this->values['uid'])?$this->values['uid']:0;
        $this->checkToken($uid);
        $name = \Lib\PhotoUtility::savePhotoWithClientData(BASEDIR. SEPARATOR .'Resources' . SEPARATOR . 'userIcon' . SEPARATOR,$this->values['icon']);
        $values['icon'] = "http://" . $_SERVER['HTTP_HOST'] . "/Resources/userIcon/0.6-" . $name;
        $row = $this->model->changeUserData($values, $this->values['uid']);
        if($row)response(200,'success',$values);
        response(400,'更改失败');
   
    }
    
    /*
     * change the background
     * post value:uid,pic
     */
    public function changeBackground(){
        $uid = isset($this->values['uid'])?$this->values['uid']:0;
        $this->checkToken($uid);
        $name = \Lib\PhotoUtility::savePhotoWithClientData(BASEDIR. SEPARATOR .'Resources' . SEPARATOR . 'background' . SEPARATOR,$this->values['background']);
        $values['background'] = "http://" . $_SERVER['HTTP_HOST'] . "/Resources/background/0.6-" . $name;
        $row = $this->model->changeUserData($values, $this->values['uid']);
        if($row)response(200,'success',$values);
        response(400,'更改失败');
         
    }
    
    /*
     * get other person data
     * post value uid
     */
    public function getUserData(){
        $otherUid = isset($this->values['uid'])?$this->values['uid']:0;
        $data = $this->model->getUserMessage('name,icon,sex,mail,province,city,county,birthday,signature',$otherUid);
        if(!empty($data))response(200,'success',$data);
        response(200,'success');
    }
    
    /*
     * change the user data
     * 修改不是统一提交，是单独修改马上提交，例如修改name，province。。。。
     * post value uid type（要修改的字段，如果是position则提交的字段有三个province，city，county）
     */
    public function changeUserData(){
        $uid = isset($this->values['uid'])?$this->values['uid']:0;
        $this->checkToken($uid);
        $type = isset($this->values['type'])?$this->values['type']:'';
        if($uid){
            $values = array();
            if($type){
                if($type == 'position'){
                    $values['province'] = $this->values['province'];
                    $values['city'] = $this->values['city'];
                    $values['county'] = $this->values['county'];
                }else{ 
                    $values[$type] = $this->values[$type];
                }
            }
/*            $values['name'] =  $this->values['name'];
            $values['sex'] =  $this->values['sex'];
            $values['mail'] =  $this->values['mail'];
            $values['birthday'] =  $this->values['birthday'];
            $values['signature'] =  $this->values['signature'];
            $values['province'] =  $this->values['province'];
            $values['city'] =  $this->values['city'];*/
            if(!empty($values))
                $row = $this->model->changeUserData($values, $uid);
            else
                response(400,'null');
            if($row || $row === 0)response(200,'success');
            response(400,'未修改');
        }
        return response(400,'检查uid是否存在');
        
        
        
    }
   
    /*
     * 获取收藏状态
     * post value:myId,bid
     */
    public function getFavoriteStatus(){
        $myId = isset($this->values['myId'])?$this->values['myId']:0;
        $this->checkToken($myId);
        $bid = isset($this->values['bid'])?$this->values['bid']:0;
        $data = $this->model->getFavoriteStatus($myId,$bid);
        if($data)response(200,'success',array('result'=>1));
        response(200,'success',array('result'=>0));
    }

}

?>