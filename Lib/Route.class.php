<?php
namespace Lib;

class Route
{
    /*
     * name of module
     */
    public static $moduleName;
    
    /*
     * method of module
     */
    public static $moduleMethod;
    
    
    public static function index($module, $method){
         self::$moduleName = $module;
        self::$moduleMethod = $method;
        self::runModule();
    }   
    
    /*
     * run the related module
     */
    public static function runModule(){
 /*           if(self::$moduleMethod != 'login' && self::$moduleMethod != 'getBlogs' && empty($_SESSION['token'])){
                response(403,'请先登录');
            }else{*/

                $classPath = '\\App\\'. self::$moduleName .'\\Control';
                $obj = new $classPath;
                $obj->{self::$moduleMethod}();
//            }

        
    }
}
