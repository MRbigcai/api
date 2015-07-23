<?php
namespace App\Search;

class Model extends \App\Common\Model
{
    /*
     * 热门搜索
     */
    public function hotSearch(){
        $sql = "select province,city,value
                from def_search
                order by num desc
                limit 0,10";
        $result = $this->db->querySql($sql);
        return $result;
    }
    
    
    /*
     * 获取个人历史发布计划
     */
    public function historyPlan($uid){
        $now = time();
        $sql = "select * from def_plan
                where uid=" .$uid. "
                and endTime >=" . $now;
        $result = $this->db->querySql($sql);
        return $result;
    
    }
    
    
    /*
     * 搜索，按博文
     */
    public function searchByBlog($content,$province,$city){
        $sql = "select *
                from def_blog
                where city='" .$city. "' 
                and province='" .$province. "' 
                and content like '%" .$content. "%'";
        
        $result = $this->db->querySql($sql);
        return $result;
    }
    
    /*
     * 搜索，按用户
     * post value province city content
     */
    public function searchByUser($name,$province,$city){
        $sql = "select uid,name,icon,sex,signature
                from def_user
                where city='" .$city. "' 
                and province='" .$province. "' 
                and name like '%" .$name. "%'";
        
        $result = $this->db->querySql($sql);
        return $result;
    }
    
    /*
     * 搜索好友
     * param name,myId
     */
    public function searchFriends($name, $myId){
       $sql = "select u.uid,u.name,u.icon,u.sex 
                from def_user u 
                inner join def_user_relation_following r 
                on r.fromUid=" .$myId. " 
                and u.uid=r.followingUid
                and name like '%" .$name. "%'";
        $result = $this->db->querySql($sql);
        return $result;
        
    
    
    }
    
    /*
     * 获取热门计划
     *
     */
    public function hotPlan(){
        $sql = "select *,count(*) counts 
                from def_plan 
                group by city ,province 
                order by counts desc 
                limit 0,10";
        $result = $this->db->querySql($sql);
        return $result;
    }
    
    

    /*
     * 搜索
     */
    public function search($values){
        //如果为全部，则blogType不应该作为判断条件
        if($values['blogType'] != 0)$sqlBlogType = " and b.blogType=" .$values['blogType'];
        else $sqlBlogType = "";
        if($values['type'] == 'all'){
            $sql = "select b.*,u.icon,u.name,if(f.uid=" .$values['myId']. ",1,0) ifFavorite,if(l.uid=" .$values['myId']. ",1,0) ifLike,if(r.fromUid=" .$values['myId']. ",1,0) ifFollowing 
                from def_blog b 
                inner join def_user u 
                on b.city='" .$values['city']. "'" .$sqlBlogType. "
                and content like '%" .$values['content']. "%'
                and b.uid=u.uid 
                left join def_favorite f 
                on f.uid=" .$values['myId']. " 
                and b.id=f.bid
                left join def_like l 
                on l.uid=" .$values['myId']. " 
                and l.bid=b.id
                left join def_user_relation_following r 
                on r.fromUid=" .$values['myId']. " 
                and r.followingUid=b.uid
                order by b.id desc
                limit 0,10";
        }elseif ($values['type'] == 'hot'){
            $sql = "select b.*,u.icon,u.name,if(f.uid=" .$values['myId']. ",1,0) ifFavorite,if(l.uid=" .$values['myId']. ",1,0) ifLike,if(r.fromUid=" .$values['myId']. ",1,0) ifFollowing
                from def_blog b
                inner join def_user u
                on b.city='" .$values['city']. "'" .$sqlBlogType. "
                and content like '%" .$values['content']. "%'
                and b.uid=u.uid
                left join def_favorite f
                on f.uid=" .$values['myId']. "
                and b.id=f.bid
                left join def_like l
                on l.uid=" .$values['myId']. "
                and l.bid=b.id
                left join def_user_relation_following r
                on r.fromUid=" .$values['myId']. "
                and r.followingUid=b.uid
                order by b.likes,b.id desc
                limit 0,10";
        }else{
            $sql = "select b.*,u.icon,u.name,if(f.uid=" .$values['myId']. ",1,0) ifFavorite,if(l.uid=" .$values['myId']. ",1,0) ifLike,if(r.fromUid=" .$values['myId']. ",1,0) ifFollowing
                from def_blog b
                inner join def_user u
                on b.city='" .$values['city']. "'" .$sqlBlogType. "
                and content like '%" .$values['content']. "%'
                and b.uid=u.uid
                left join def_favorite f
                on f.uid=" .$values['myId']. "
                and b.id=f.bid
                left join def_like l
                on l.uid=" .$values['myId']. "
                and l.bid=b.id
                inner join def_user_relation_following r
                on r.fromUid=" .$values['myId']. "
                and r.followingUid=b.uid
                order by b.id desc
                limit 0,10";
            
        }
        $result = $this->db->querySql($sql);
        return $result;
    }
}

?>