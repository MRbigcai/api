<?php
namespace App\Home;
class Model extends \App\Common\Model
{
    /*
     * get blogs from db
     * param array
     * return array
     */

    public function getBlogs($values, $pageSize){
        $page = isset($values['page'])?$values['page']:1;
//        $pageSize = 2;
        $offset=$pageSize*($page-1);
        $sql = "select b.*,u.icon,u.name 
                from def_user u 
                right join 
                    (select * from def_blog 
                order by time desc) b 
                on b.uid=u.uid
                limit " .$offset. "," .$pageSize;
        $data = $this->db->querySql($sql);
        return $data;
    }
    
    
    /*
     * Comment on the blog post
     *
     */
    public function comment($valuesArr){
        $row = $this->db->insert('def_comments', $valuesArr)->exec();
        if($row)return $row;
        return '';
    }
    
    /*
     * reply on the blog post
     *
     */
    public function reply($valuesArr){
        $row = $this->db->insert('def_replies', $valuesArr)->exec();
        if($row)return $row;
        return '';
    }
    
    /*
     * get the comments of a blog
     *
     */
    public function getComments($bid){
       return $result = $this->db->querySql("select c.*,d.name from def_comments c inner join def_user d on c.bid=" .$bid. " and c.uid=d.uid;");           
    }
    
    /*
     * get the replies of a blog
     *
     */
    public function getReplies($bid){
        return $result = $this->db->querySql("select r.*,d.name fromName,u.name toName from def_replies r inner join def_user d on r.bid=" .$bid. " and r.fromid=d.uid left join def_user u on r.toid=u.uid");
    }
}

?>