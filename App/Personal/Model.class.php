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
    public function getFollowingMessage($myId,$theOtherId,$page,$pageSize){
        $offset=$pageSize*($page-1);
        $data['followingCount'] = $this->db->select('followingCount')->from('def_user')->where('uid=' . $theOtherId)->query()->fetchAll();
        $allIdArr = $this->db->select('followingUid')->from('def_user_relation_following')->where('fromUid = ' . $theOtherId . ' and followingUid!='. $myId)->limit($offset, $pageSize)->query()->fetchAll();
        if(empty($allIdArr))response(200,'空');
        $allIdString = '';
        foreach ($allIdArr as $key => $value){
            $allIdString .= $value['followingUid'] .",";
        }
        $allIdString = trim($allIdString,',');
        $data['followingMessage'] = $this->getUserMessageWithFollower('uid,name,icon,sex,signature', $allIdString, $myId);
        return $data;
        
    }
    
    /*
     *
     *
     */
    public function getUserMessageWithFollower($fieldString, $idString, $myId){
        $sql = "select f1.*,if(f2.followerUid=" .$myId. ",1,0) isFollowing
                from def_user_relation_follower f2
                right join
                    (select " .$fieldString. "
                    from def_user
                    where uid
                    in (" .$idString. ")) f1
                on f1.uid=f2.fromUid
                and f2.followerUid=" .$myId;
/*echo	$sql = "select f1.name,f1.icon,f1.uid,f1.sex,f1.blogCount,f1.followingCount,f1.followerCount,f1.background,if(f2.followerUid=1139,1,0) isFollowing 
                from def_user f1 
                inner join def_user_relation_follower f2
                on f1.uid in (" .$idString. ") 
                and f1.uid=f2.fromUid 
                and f2.followerUid=" .$myId;*/
        return $userMessage = $this->db->querySql($sql);
        //      return $userMessage = $this->db->select($fieldString)->from('def_user')->where('uid in (' . $idString . ')')->query()->fetchAll();
    }
    
    /*
     * get the userId of the follower
     * param int userId
     * return followerArr
     * 
     */
    
    public function getFollowerMessage($myId,$theOtherId,$page,$pageSize){
        $offset=$pageSize*($page-1);
        //followerCount别名成followingCount只是为了方便，前端思源提的需求，扁他
        $data['followerCount'] = $this->db->select('followerCount followingCount')->from('def_user')->where('uid=' . $theOtherId)->query()->fetchAll();
        $allIdArr = $this->db->select('followerUid')->from('def_user_relation_follower')->where('fromUid = ' . $theOtherId . " and followerUid!=" .$myId)->limit($offset, $pageSize)->query()->fetchAll();
        if(empty($allIdArr))response(200,'暂无粉丝');
        $allIdString = '';
        foreach ($allIdArr as $key => $value){
            $allIdString .= $value['followerUid'] .",";
        }
        $allIdString = trim($allIdString,',');
        $data['followerMessage'] = $this->getUserMessageWithFollower('uid,name,icon,sex,signature', $allIdString, $myId);
        return $data;
        
    }
    
    
    /*
     * add following
     */
    public function addFollowing($value){
        if(!is_numeric($value['fromUid']) || !is_numeric($value['followingUid']))return '';
        $row1 = $this->db->insert('def_user_relation_following', $value)->exec();
        $values['fromUid'] = $value['followingUid'];
        $values['followerUid'] = $value['fromUid'];
        $values['addTime'] = $value['addTime'];
        $row2 = $this->db->insert('def_user_relation_follower', $values)->exec();
        if($row1 && $row2){
            $this->db->execSql("update def_user set followingCount=followingCount+1 where uid=" . $value['fromUid']);
            $this->db->execSql("update def_user set followerCount=followerCount+1 where uid=" . $value['followingUid']);
            return true;
            
        }
        return '';
    }
    
    /*
     * remove following
     */
    public function removeFollowing($value){
        if(!is_numeric($value['fromUid']) || !is_numeric($value['followingUid']))return '';
        $row1 = $this->db->delete()->from('def_user_relation_following')->where('fromUid=' . $value['fromUid'] . ' and followingUid=' . $value['followingUid'])->exec();
        $row2 = $this->db->delete()->from('def_user_relation_follower')->where('fromUid=' . $value['followingUid'] . ' and followerUid=' . $value['fromUid'])->exec();
        if($row1 && $row2){
            $this->db->execSql("update def_user set followingCount=followingCount-1 where uid=" . $value['fromUid']. " and followingCount>0");
            $this->db->execSql("update def_user set followerCount=followerCount-1 where uid=" . $value['followingUid']. " and followerCount>0");
            return true;
        }
        return '';
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
     * 取消收藏
     */
    public function removeFavorite($value){
        $row = $this->db->delete()->from('def_favorite')->where('uid=' . $value['uid'] . ' and bid=' . $value['bid'])->exec();
        if($row){
            $this->db->execSql("update def_user set favoriteCount=favoriteCount-1 where uid=" . $value['uid']. " and favoriteCount>0");
            return true;
        }
        return '';
    }
    /*
     * get the favorite of a blog
     *
     */
    public function getFavoriteList($uid){
        $sql = "select b.* from def_blog b
                inner join def_favorite f
                on f.uid=" .$uid. "
                and f.bid=b.id
                order by b.province,b.city";
        return $result = $this->db->querySql($sql);
    }
    
/*
     * 点赞博文
     * param array valueArr
     * return int row
     */
    public function addLike($valueArr){
        $row = $this->db->insert('def_like', $valueArr)->exec();
        if($row){
            $row = $this->db->execSql("update def_blog set likes=likes+1 where id=" . $valueArr['bid']);
            if($row)return $row;
        }
        return '';
        
    }
/*
     * 取消点赞
     */
    public function removeLike($value){
        $row = $this->db->delete()->from('def_like')->where('uid=' . $value['uid'] . ' and bid=' . $value['bid'])->exec();
        if($row){
            $this->db->execSql("update def_blog set likes=likes-1 where uid=" . $value['uid']. " and likes>0");
            return true;
        }
        return '';
    }
    /*
     * get the favorite of a blog
     *
     */
    public function getLikeUsers($bid){
        $sql = "select u.uid,u.icon 
                from def_user u
                inner join def_like l
                on l.bid=" .$bid. "
                and l.uid=u.uid";
        return $result = $this->db->querySql($sql);
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
    
    
    /*
     * 获取收藏状态
     * post value:myId,bid
     */
    public function getFavoriteStatus($myId,$bid){
        $sql = "select 1 from def_favorite
                where uid=" .$myId. "
                and bid=" .$bid;
        $result = $this->db->querySql($sql);
        return $result;
    
    }
    

}

?>
