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
    
    /*添加出行计划
     *param array $values 
     * 
     */
    public function insertPlan($values){
        $row = $this->db->insert('def_plan', $values)->exec();
        if($row)return $row;
       
        
    }
    
    /*
     * 获取相同的出行计划
     * 
     * param array $values
     */
    public function getPlanInCommon($values){
        $sql = "select p.uid,u.icon,u.signature,u.name,if(f.followerUid=" .$values['uid']. ",1,0) isFollowing 
                from def_plan p inner join def_user u 
                on  p.province='" .$values['province']. "' 
                and p.city='" .$values['city']. "' 
                and p.startTime<=" .$values['startTime']. "
                and p.endTime>=" .$values['endTime']."
                and p.uid!=" .$values['uid']. "
                and p.uid=u.uid";
        $keyArr = array('county','location','sex','age');
        foreach ($keyArr as $k => $v){
            if(!empty($values[$v])){
                $sql .= " and p." .$v. "='" .$values[$v]. "'";
            }
        }
        $sql .= " left join def_user_relation_follower f 
                 on f.fromUid=p.uid 
                 and f.followerUid=" .$values['uid'];
        $result = $this->db->querySql($sql);
        return $result;
        
           
    }
}
?>