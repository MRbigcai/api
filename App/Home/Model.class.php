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
        $sql = "select b.*,u.icon,u.name,if(f.uid=" .$values['myId']. ",1,0) ifFavorite,if(l.uid=" .$values['myId']. ",1,0) ifLike,if(r.fromUid=" .$values['myId']. ",1,0) ifFollowing 
                from def_blog b 
                left join def_user u 
                on b.uid=u.uid 
                left join def_favorite f 
                on f.uid=" .$values['myId']. " 
                and b.id=f.bid
                left join def_like l 
                on l.uid=" .$values['myId']. " 
                and l.bid=b.id
                left join def_user_relation_following r 
                on r.fromUid=" .$values['myId']. " 
                and r.followingUid=b.uid
                order by b.time desc
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
        return $result = $this->db->querySql("select r.*,d.name fromName,d.icon fromIcon,u.name toName from def_replies r inner join def_user d on r.bid=" .$bid. " and r.fromId=d.uid left join def_user u on r.toId=u.uid");
    }
    

}

?>