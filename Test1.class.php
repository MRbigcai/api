<?php
include_once 'Test2.class.php';


class Test1 extends Test2\Test2
{
    public $a;
    function __construct(){
        $this->a = 'test1';
        
    }
}
class Model{
    public $m;
    public function __construct(){
        $this->m = 'test1';
        
    }
}
$b = new Test1();
var_dump($b);

?>