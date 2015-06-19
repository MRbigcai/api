<?php
namespace Test2;
class Test2
{
    public $test2;
    public $model;
    public function __construct(){
        $this->test2 = '[af';
        $this->model = new Model();
    }
}

class Model{
    public $m;
    public function __construct(){
        $this->m = 'Test2\Model';
    }
    
}
?>