<?php
namespace Lib;

class Session
{
    private $lifeTime;
    private $dbh;
    private $sessionId;
    private $data;
    
    public function open($session_path, $session_name)
    {
        global $db,$postValues;
        $this->lifeTime = 3600;
        $this->data = $postValues;
        $this->dbh = $db;
        if(empty($postValues['token'])){
            $postValues['token'] = $this->sessionId = md5('phone'.time());
        }
        else
            $this->sessionId = $postValues['token'];
        return true;
    }
    
    
    public function read($sessID)
    {
        $row = $this->dbh->select('sessionData as d')->from('def_sessions')->where("sessionId='" . $this->sessionId . "' and sessionExpires>" . time())->query()->fetch();
 //        $res = mysql_query("SELECT session_data AS d FROM ws_sessions WHERE session_id = '$sessID' AND session_expires > ".time(),$this->dbHandle); 
        // return data or an empty string at failure 
        if($row) 
            return $row['d']; 
        return "";
    }
    
    public function write($sessID,$sessData) {
        // new session-expire-time
        $newExp = time() + $this->lifeTime;
        // is a session with this id in the database?
        $row = $this->dbh->select('*')->from('def_sessions')->where("sessionId='" . $this->sessionId . "'")->query()->fetch();
        // if yes,
        if($row) {
            // ...update session-data
            $result = $this->dbh->update('def_sessions', array('sessionExpires'=>$newExp,'sessionData'=>$sessData))->where("sessionId='" . $this->sessionId . "'")->exec();
            // if something happened, return true
            if($result)
                return true;
        }
        // if no session-data was found,
        else {
            // create a new row
            $data = array(
                'sessionId' => $this->sessionId,
                'sessionExpires' => $newExp,
                'sessionData' => $sessData
            );
            $result = $this->dbh->insert('def_sessions', $data)->exec();

            // if row was created, return true
            if($result)
                return true;
        }
        // an unknown error occured
        return false;
    }
    
    public function destroy($sessID) {
        // delete session-data
        $result = $this->dbh->delete()->from('def_sessions')->where('sessionId=' . $this->sessionId);
        // if session was deleted, return true,
        if($result)
            return true;
        // ...else return false
        return false;
    }
    public function gc($sessMaxLifeTime) {
        // delete old sessions
        $result = $this->dbh->delete()->from('def_sessions')->where('sessionExpires<' . time());
        // return affected rows
        return $result;
    }
    
    public function close() {
        $this->gc(ini_get('session.gc_maxlifetime'));
        // close database-connection
        return;
    }
    
    public function __construct(){
        session_set_save_handler(
            array($this, "open"),
            array($this, "close"),
            array($this, "read"),
            array($this, "write"),
            array($this, "destroy"),
            array($this, "gc")
        );
    }
   
}

?>