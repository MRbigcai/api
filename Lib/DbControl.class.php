<?php
namespace Lib;

class DbControl
{
    private $dsn;
    private $user;
    private $pwd;
    private static $_instance;
    private $dbh;
    private $sth;
    private $sql;
    private $rows;
    
    private function __construct(){}
    
    /*
     * 单例实例化
     */
    public static function getInstance(){
        if(!(self::$_instance instanceof self))
            self::$_instance = new self();
        else 
            return '';
        return self::$_instance;
    }
    
    /*
     * connect db
     * param host,user,pwd,dbName
     */
    public function connect($host, $user, $pwd, $dbName){
        $this->dsn = 'mysql:dbname=' . $dbName . ';host=' . $host;
        $this->user = $user;
        $this->pwd = $pwd;
        try {
            $this->dbh = new \PDO($this->dsn, $this->user, $this->pwd);
            $this->dbh->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            response(401,'connect db wrong '. $e->getMessage());
        }
        
    }
    
    /*
     * from which table
     * param string $table
     * return object
     */
    public function from ($table){
        $this->sql .= ' from ' . $table;
        return $this;
        
    }
    
    /*
     * condition of where
     * param string $where
     * return object
     */
    public function where ($where){
        $this->sql .= ' where '.$where;
        return $this;
    } 
    
    /*
     * select field
     * param string $field
     * return object
     */
    public function select ($field){
        $this->sql .= 'select ' . $field;
        return $this;
        
    }
    
    /*
     * query
     * return object
     */
    public function query(){
        try{
            $this->dbh->query("set names utf8");
            $this->sth = $this->dbh->query($this->sql);
        }catch(\PDOException $e){
            file_put_contents(BASEDIR . SEPARATOR . "logs" .SEPARATOR . "mysqlError.txt", date("l dS \\of F Y h:i:s A") . "  " . $this->sql . "\r\n", FILE_APPEND);
            response(401,"error query:" . $e->getMessage());
            
        }
        return $this;
        
    }
    
    /*
     * get the values from query 
     * return array
     */
    public function fetchAll(){
        $result = $this->sth->fetchAll(\PDO::FETCH_ASSOC);
        $this->sql = '';
        return $result;
        
    }
    
    /*
     * get the value from query
     * return array
     */
    public function fetch(){
        $result = $this->sth->fetch(\PDO::FETCH_ASSOC);
        $this->sql = '';
        return $result;
    }
    
    /*
     * run the sql of insert/update/del
     * 
     * return int rows
     */
    public function exec(){
        try{
            $this->dbh->query("set names utf8");
            $this->rows = $this->dbh->exec($this->sql);
            $this->sql = '';
        }catch(\PDOException $e){
            file_put_contents(BASEDIR . SEPARATOR . "logs" .SEPARATOR . "mysqlError.txt", date("l dS \\of F Y h:i:s A") . "  " . $this->sql . "\r\n", FILE_APPEND);
            response(401,"error exec:" . $e->getMessage());
            
        }
        return $this->rows;
        
    }
    
    /*
     * insert into db
     * param array values
     * return object
     */
    public function insert($table, $fieldArr){
        if(isset($fieldArr)){
            $fieldAll = '';
            $values = '';
            foreach($fieldArr as $key => $value){
                if(!empty($value)){
                    $fieldAll .= $key . ',';
                    $values .= "'" . $value . "',";
                }
                 
            }
            $this->sql .= "insert into " . $table . " (" . rtrim($fieldAll, ',') . ") values (" . trim($values, ',') . ")";
        }
        return $this;
    }
    
    /*
     * update the field
     * param array
     * return object
     */
    public function update($table, $fieldArr){
        if(isset($fieldArr)){
            $fieldAll = '';
            $values = '';
            foreach ($fieldArr as $key => $value){
                $fieldAll .= $key . "='" . $value . "',";
            }
            $this->sql .= "update " . $table ." set " . rtrim($fieldAll,",");
        }
        return $this;
    }
    
    /*
     * delete
     * return object
     */
    public function delete(){
        $this->sql .= "delete";
        return $this;
       
        
    }
    /*
     * limit
     * param int startPage endPage
     */
    public function limit ($start,$num){
        $this->sql .= " limit " . $start . "," . $num;
        return $this;
        
    }
    
    /*
     * get the last insert id
     */
    public function lastInsertId(){
        return $this->dbh->lastInsertId();
    }
    
    /*
     * query sql directly
     * param string sql
     * retuan array
     * 
     */
    public function querySql($sql){
        $this->sql = $sql;
        return $this->query()->fetchAll();        
    }
    
    /*
     * exec sql directly
     * param string sql
     * retuan array
     *
     */
    public function execSql($sql){
        $this->sql = $sql;
        return $this->exec();
    }
    /*
     * print the sql
     * return string
     */
    public function printSql(){
        print_r($this->sql);
 //return $this->sql;
    
    }
}

