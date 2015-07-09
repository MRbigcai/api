<?php
namespace App\Common;

class Model
{
    protected $db;
    
    public function __construct(){
        $this->db = $GLOBALS['db'];
    }
    
    /*
     * get the user message by uid
     * param string idString,use ',' to separate
     * param string fieldString
     * return array userMessage
     */
    public function getUserMessage($fieldString, $idString){
        return $userMessage = $this->db->select($fieldString)->from('def_user')->where('uid in (' . $idString . ')')->query()->fetchAll();
    }
    
    /*get the blog message by user id
     * param int uid
     * param string 
     * return array $userMessArr
     */
    public function getblogByUid($uid,$page,$pageSize){
        $offset=$pageSize*($page-1);
        $sql = "select b.*,u.name,u.icon 
                from def_blog b 
                inner join def_user u 
                on b.uid=".$uid."
                and b.uid=u.uid 
		order by b.time desc
		limit " .$offset. "," .$pageSize;
 /*       $sql = "select b.*,u.icon,u.name
                from def_user u
                right join
                    (select * from def_blog 
                    where owner=" .$uid. "
                    limit " .$offset. "," .$pageSize. ") b
                on b.owner=u.uid";*/
        $blogMessage = $this->db->querySql($sql);
        return $blogMessage;
 //       return $this->db->querySql('select `blog`,`picture`,`comment`,`like`,`pic_high`,`pic_width`,`longtitude`,`latitude` from def_blog where owner = 1109');
//        return $blogMessage = $this->db->select($fieldString)->from('def_blog')->where('owner = ' . $uid)->limit($offset, $pageSize)->query()->fetchAll();
    }
    
    /*
     * get the user message by phone
     * param string $phone,use ',' to separate
     * param string fieldString
     * return array userMessage
     */
    public function getUserMessageByPhone($fieldString, $phone){
        return $userMessage = $this->db->select($fieldString)->from('def_user')->where('phone=' . $phone)->query()->fetchAll();
    }
    
    
    /*
     * 通过账号和token验证用户合法性
     */
    public function checkToken($value){
       $sql = "select id
                from def_user_token
                where uid=".$value['uid']."
                and token='".md5($value['token'])."'
                and expire>".time();
        $reslut = $this->db->querySql($sql);
        if(!empty($reslut[0]['id']) && is_numeric($reslut[0]['id']))return $reslut[0]['id'];
        return '';
    
    
    
    }
    /*
     * 查询是否关注
     * 
     */
    public function checkIfFollowing($myId,$theOtherId){
       $sql = "select 1 
               from def_user_relation_following
               where fromUid=" .$myId. "
               and followingUid=" .$theOtherId;
       $result = $this->db->querySql($sql);
       return $result;
        
    }
}

?>
