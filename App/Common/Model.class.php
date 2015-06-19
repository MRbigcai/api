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
    public function getblogByUid($fieldString,$uid,$page,$pageSize){
        $offset=$pageSize*($page-1);
 //       return $this->db->querySql('select `blog`,`picture`,`comment`,`like`,`pic_high`,`pic_width`,`longtitude`,`latitude` from def_blog where owner = 1109');
        return $blogMessage = $this->db->select($fieldString)->from('def_blog')->where('owner = ' . $uid)->limit($offset, $pageSize)->query()->fetchAll();
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
}

?>