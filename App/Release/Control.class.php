<?php
namespace App\Release;

class Control extends \App\Common\Control
{
    public function insertBlogs(){
        //save the photo
        $name = \Lib\PhotoUtility::savePhotoWithClientData(BASEDIR. SEPARATOR .'Resources' . SEPARATOR . 'blog' . SEPARATOR,$this->values['pic']);
        $this->values['pic'] = "http://" . $_SERVER['HTTP_HOST'] . "/Resources/blog/" . $name;
        //require the table field
        foreach ($this->config->insertField as $k => $v){
                $values[$v] = !empty($this->values[$v])?$this->values[$v]:'';
        }
        $row = $this->model->insertBlogs($values);
        if($row)response(200,'success');
        
    }
}

?>