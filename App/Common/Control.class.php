<?php
namespace App\Common;

class Control
{
    public $values;
    public $model;
    public $config;
    /*
     * init the values from post_array，then instance the model
     */
    public function __construct(){
        global $postValues,$config;
        $this->values = $postValues;
        $classPath = '\\App\\'. $this->values['module'] .'\\Model';
        $configFile = BASEDIR.SEPARATOR . "App" . SEPARATOR.ucfirst($this->values['module']).SEPARATOR."config.php";
        if(file_exists($configFile)){
            include_once $configFile;
            $this->config = $config->{$this->values['module']};
        }
        $this->model = new $classPath;
    }
    

}

?>