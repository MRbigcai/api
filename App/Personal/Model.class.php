<?php
namespace App\Personal;

class Model extends \App\Common\Model
{
    /*
     * get the following in common
     * param int myId,current user id 
     * param int theOtherId,the selected user id
     * return array followingInCommon
     */
    public function getFollowingInCommon($myId, $theOtherId){
        //get all the uid in common
       $sql = "select followingUid from def_user_relation_following 
                where fromUid=" . $myId . " 
                and followingUid in 
                (select followingUid from def_user_relation_following where fromUid=" . $theOtherId .");";
        $allIdArr = $this->db->querySql($sql);
        if($allIdArr){
            $followingIdInCommon = '';
            foreach ($allIdArr as $key => $value){
                $followingIdInCommon .= $value['followingUid'] .",";
            }
            $followingIdInCommon = trim($followingIdInCommon,',');
            $followingMessageInCommon = $this->getUserMessage('icon', $followingIdInCommon);
            return $followingMessageInCommon;
        }
        return array();
        
    }
    
    
    /*
     * get the userId that the selected user following
     * param int userId
     * param int $page
     * param int $pageSize
     * return followingArr
     */
    public function getFollowingMessage($uid,$page,$pageSize){
        $offset=$pageSize*($page-1);
        $allIdArr = $this->db->select('followingUid')->from('def_user_relation_following')->where('fromUid = ' . $uid)->limit($offset, $pageSize)->query()->fetchAll();
        $allIdString = '';
        foreach ($allIdArr as $key => $value){
            $allIdString .= $value['followingUid'] .",";
        }
        $allIdString = trim($allIdString,',');
        $followingMessage = $this->getUserMessage('uid,name,icon,sex', $allIdString);
        return $followingMessage;
        
    }
    
    /*
     * get the userId of the follower
     * param int userId
     * return followerArr
     * 
     */
    
    public function getFollowerMessage(){
        
        
    }
    
    
    /*
     * add following
     */
    public function addFollowing($value){
        if(!is_numeric($value['fromUid']) || !is_numeric($value['followingUid']))return '';
        $row = $this->db->insert('def_user_relation_following', $value)->exec();
        $values['fromUid'] = $value['fromUid'];
        $values['followerUid'] = $value['followingUid'];
        $values['addTime'] = $value['addTime'];
        $row = $this->db->insert('def_user_relation_follower', $values)->exec();
        return $row;
    }
    
    /*
     * favorite the blog
     * param array valueArr
     * return int row
     */
    public function addFavorite($valueArr){
        $row = $this->db->insert('def_favorite', $valueArr)->exec();
        if($row){
            $row = $this->db->execSql("update def_user set favoriteCount=favoriteCount+1 where uid=" . $valueArr['uid']);
            if($row)return $row;
        }
        return '';
        
    }
    
    
    /*
     * reset pwd
     */
    public function resetPwd($valuesArr, $phone){
        return $row = $this->db->update('def_user', $valuesArr)->where('phone=' . $phone)->exec();               
    }
    
    
    /*
     * change the face
     * post value:uid,pic
     */
    public function changeUserData($valuesArr, $uid){
        return $this->db->update('def_user', $valuesArr)->where('uid=' . $uid)->exec();
    }
    

}

?>