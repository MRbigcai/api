<?php
namespace App\Release;
class Control extends \App\Common\Control
{
    
    /*  发表博文
     * post value 'content','uid','pic','pic_high','pic_width','longtitude','latitude','blogType','detailType'
     * 
     */
    public function insertBlogs(){
        $myId = isset($this->values['uid'])?$this->values['uid']:0;
        $this->checkToken($myId);
        //save the photo
        $name = \Lib\PhotoUtility::savePhotoWithClientData(BASEDIR. SEPARATOR .'Resources' . SEPARATOR . 'blog' . SEPARATOR,$this->values['pic']);
        $this->values['pic'] = "http://" . $_SERVER['HTTP_HOST'] . "/Resources/blog/" . $name;
        //require the table field
        foreach ($this->config->insertField as $k => $v){
                $values[$v] = !empty($this->values[$v])?$this->values[$v]:'';
        }
        $values['time'] = time();
        $row = $this->model->insertBlogs($values);
        if($row)response(200,'success');
        
    }
 
    /*
     * 发布出行计划
     * post value 'startTime','endTime','province','city','county','location','sex','age','uid'
     */    
    public function releasePlan(){
        $myId = $this->values['uid'];
        $this->checkToken($myId);
        if(empty($this->values['province']) || empty($this->values['city']))response(400, '省市不能为空');
        if(empty($this->values['startTime']) || empty($this->values['endTime']))response(400, '时间不能为空');
        $values = array();
        $keyArr = array('startTime','endTime','province','city','county','location','sex','age','uid');
        //80前-800，80后-801，90后-901，00后-001，不限-0
        foreach ($keyArr as $k => $v){
            if(!empty($this->values[$v])){
                if($v == 'startTime' || $v == 'endTime')
                    $values[$v] = strtotime($this->values[$v]);
                else 
                    $values[$v] = $this->values[$v];
            }
        }
        $values['time'] =  time();
        $row = $this->model->insertPlan($values);
        if($row){
            $data = $this->model->getPlanInCommon($values);
            if($data)response(200,'success',$data);
            else response(200, "success", array());
        }
        return '';
    
    }
}

?>