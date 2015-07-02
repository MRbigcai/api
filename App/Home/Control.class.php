<?php
namespace App\Home;

class Control extends \App\Common\Control
{
    /*
     * get the blogs
     * post value: page
     */
    public function getBlogs(){
        $data = $this->model->getBlogs($this->values, $this->config->pageSize);
        if(!empty($data)){
            response(200,'success',$data);            
        }
    }
    
    /*
     * Comment on the blog post 
     * post values:bid,uid,content
     */
    public function comment(){
        $uid = isset($this->values['uid'])?$this->values['uid']:0;
        $bid = isset($this->values['bid'])?$this->values['bid']:0;
        if(empty($this->values['content']))response(400,'内容不能为空哦');
        $values['content'] = $this->values['content'];
        $this->checkToken($uid);
        $values['uid'] = $uid;
        $values['bid'] = $bid;
        $values['time'] = time();
        $row = $this->model->comment($values);
        if($row) response(200,'success');
        
        
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
        response(200,'success',$data);
        
    }
}

?>