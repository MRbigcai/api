<?php
namespace App\Search;

class Control extends \App\Common\Control
{
    /*
     * 热门搜索
     */
    public function hotSearch(){
        $data = $this->model->hotSearch();
        if($data)response(200,'success',$data);
        response(200,'success');
    }
    
    /*
     * 获取个人历史发布计划
     */
    public function historyPlan(){
        if(!is_numeric($this->values['myId']))response(400,'用户id不存在');
        $uid = $this->values['myId'];
        $this->checkToken($uid);
        $data['data'] = $this->model->historyPlan($uid);
        if(!empty($data))response(200,'success',$data);
        response(200,'success');
    }
    
    /*
     * 获取热门计划
     * 
     */
    public function hotPlan(){
       $data['data'] = $this->model->hotPlan();
       if($data)response(200,'success',$data);
       response(200,'data is empty');
    }
    
    /*
     * 搜索，按博文
     */
    public function searchByBlog(){
        $content = isset($this->values['content'])?$this->values['content']:response(400,'内容不能为空');
        $province = isset($this->values['province'])?$this->values['province']:response(400,'省不能为空');
        $city = isset($this->values['city'])?$this->values['city']:response(400,'省不能为空');
        $data['result'] = $this->model->searchByBlog($content,$province,$city);
        if(!empty($data))response(200,'success',$data);
        response(200,'success');
        
    }
    
    
    /*
     * 搜索，按用户
     * post value province city content
     */
    public function searchByUser(){
        $name = isset($this->values['name'])?$this->values['name']:response(400,'内容不能为空');
        $province = isset($this->values['province'])?$this->values['province']:response(400,'省不能为空');
        $city = isset($this->values['city'])?$this->values['city']:response(400,'省不能为空');
        $data['result'] = $this->model->searchByUser($name,$province,$city);
        if(!empty($data))response(200,'success',$data);
        response(200,'success');
    }
    
    
    /*
     * 搜索好友
     * post value name,myId
     */
    public function searchFriends(){
        $myId = isset($this->values['myId'])?$this->values['myId']:0;
        $name = isset($this->values['name'])?$this->values['name']:'';
        $data['result'] = $this->model->searchFriends($name, $myId);
        if($data)response(200,'success',$data);
        response(200,'success');
        
        
    }
}

?>