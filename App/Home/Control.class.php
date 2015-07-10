<?php
namespace App\Home;

class Control extends \App\Common\Control
{
    /*
     * get the blogs
     * post value: page
     */
    public function getBlogs(){
        $values = $this->values;
        $values['myId'] = isset($this->values['myId'])?$this->values['myId']:0;
        $data = $this->model->getBlogs($values, $this->config->pageSize);
        if(!empty($data)){
            response(200,'success',$data);            
        }
        response(200,'empty');
    }
    
    /*
     * Comment on the blog post 
     * post values:bid,uid,content
     */
    public function comment(){
        $uid = isset($this->values['uid'])?$this->values['uid']:0;
        $bid = isset($this->values['bid'])?$this->values['bid']:0;
        if(empty($this->values['content']))response(400,'内容为空');
        $values['content'] = $this->values['content'];
        $this->checkToken($uid);
        $values['uid'] = $uid;
        $values['bid'] = $bid;
        $values['time'] = time();
        $row = $this->model->comment($values);
        if($row) response(200,'success');
        response(400,'评论失败');
        
        
    }
    
    /*
     * reply
     * post value:fromId,toId,bid,content
     */
    public function reply(){
        $fromId = isset($this->values['fromId'])?$this->values['fromId']:0;
        $toId = isset($this->values['toId'])?$this->values['toId']:0;
        $bid = isset($this->values['bid'])?$this->values['bid']:0;
        $this->checkToken($fromId);
        $values['fromId'] = $fromId;
        $values['toId'] = $toId;
        $values['bid'] = $bid;
        $values['content'] = $this->values['content'];
        $values['time'] = time();
        $row = $this->model->reply($values);
        if($row) response(200,'success');
        response(400,'回复失败');
    }
    
    /*
     * get the comments of a blog
     * post value:bid
     * 
     */
    public function getCommentsAndReplies(){
        $bid = isset($this->values['bid'])?$this->values['bid']:0;
        if(!is_numeric($bid))response(400,'博文不存在');
        $comments = $this->model->getComments($bid);
        $replies = $this->model->getReplies($bid);
        $data = array();
        if($comments)$data['comments'] = $comments;
        if($replies)$data['replies'] = $replies;
        if(!empty($data))response(200,'success',$data);
        response(200,'success');
        
    }
    /*
     * 检查是否关注
     * 
     */
    public function checkIfFollowing(){
        $myId = isset($this->values['myId'])?$this->values['myId']:response(400,'myId is error');
        $theOtherId = isset($this->values['theOtherId'])?$this->values['theOtherId']:response(400,'theOtherId is error');
        $data = $this->model->checkIfFollowing($myId,$theOtherId);
        if($data)response(200,'已关注',array('data'=>1));
        response(200,'未关注',array('data'=>0));
        
        
        
    }
    
    /*
     * 验证是否登录或者token是否过期的单独接口，用于广告部分
     */
    public function checkTokenApi(){
        $value['uid'] = isset($this->values['uid'])?$this->values['uid']:0;
        if(!empty($this->values['token']))
            $value['token'] = $this->values['token'];
        else
            $value['token'] = '';
        $value['time'] = time();
        $row = $this->model->checkToken($value);
        if($row)response(200,'已登录');
        response(400,'未登录');
    
    }

}

?>