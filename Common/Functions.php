<?php 

spl_autoload_register('autoLoad');

/*
 * auto load class
 * param string class
 * 
 */
function autoLoad($class){

    $class = str_replace("\\",SEPARATOR,$class);
    include_once(BASEDIR. SEPARATOR. $class. ".class.php");
    
} 


/*
 * response to client with json
 * param int code   status of result
 * param string message 
 * parm array   real data to client
 * return json
 */
/**
 * @param int $flag
 * @param string $retMsg
 * @param array $data
 * @return string
 */
function response($flag = 200,$retMsg = '',$data = array('result'=>'empty')){
    if(!is_numeric($flag))return '';

    $result = array(
        'flag' => $flag,
        'retMsg' => $retMsg,
        'data' => $data
    );
    echo json_encode($result);
    exit;
} 