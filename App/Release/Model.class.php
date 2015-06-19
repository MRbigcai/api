<?php
namespace App\Release;

class Model extends \App\Common\Model
{
    /*
     * insert the blogs into the table
     * param array
     */
    public function insertBlogs($values){
        $row = $this->db->insert('def_blog', $values)->exec();
        if($row)return $row;
    }
}
?>