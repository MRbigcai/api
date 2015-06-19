<?php
namespace App\Map;

class Control extends \App\Common\Control
{
    /*
     * get the blogs
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
        if(!is_numeric($this->values['bid']) || !is_numeric($this->values['uid']))response(400,'博文或用户不存在');
        $values['uid'] = $this->values['uid'];
        $values['bid'] = $this->values['bid'];
        $values['content'] = $this->values['content'];
        $values['time'] = time();
        $row = $this->model->comment($values);
        if($row) response(200,'success');
        
        
    }
    
    /*
     * reply
     * post value:fromId,toId,bid,content
     */
    public function reply(){
        if(!is_numeric($this->values['formId']) || !is_numeric($this->values['toId']))response(400,'用户不存在');
        $values['formId'] = $this->values['formId'];
        $values['toId'] = $this->values['toId'];
        $values['bid'] = $this->values['bid'];
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
        if(!is_numeric($this->values['bid']))response(400,'博文不存在');
        $comments = $this->model->getComments($this->values['bid']);
        $replies = $this->model->getReplies($this->values['bid']);
        $data = array();
        if($comments)$data['comments'] = $comments;
        if($replies)$data['replies'] = $replies;
        response(200,'success',$data);
        
    }
}

?>