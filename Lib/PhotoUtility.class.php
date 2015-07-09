<?php
namespace Lib;
class PhotoUtility
{
    public static function getPhotoBinaryData($data)
    {
        $binary = str_replace(' ','',$data);
        $binary = str_ireplace("<",'',$binary);
        $binary = str_ireplace(">",'',$binary);
        $binary = pack("H*",$binary);
        
        return $binary;
    }
    
    public static function savePhotoWithBinary($path, $binary)
    {
        $name = time() . ".jpg";
        $file = fopen($path . $name, "w");
        fwrite($file, $binary);
        fclose($file);
        self::resize($path,$name,180);
        self::resize($path,$name,750);
        return $name;
    }
    
    public static function savePhotoWithClientData($path, $data)
    {
        $binary = PhotoUtility::getPhotoBinaryData($data);
        return PhotoUtility::savePhotoWithBinary($path, $binary);
    }   
    
    public static function resize($path, $filename, $w=180){     
        $file = $path.$filename;
        // 内容类型
        //header('Content-Type: image/jpeg');
        
        // 获取新的尺寸
        list($width, $height) = getimagesize($file);
        
        $new_width = ($w>$width)?$width:$w;
        $new_height = $height * ($new_width/$width);
        
        // 重新取样
        $image_p = imagecreatetruecolor($new_width, $new_height);
        $image = imagecreatefromjpeg($file);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        
        // 输出图片压缩为180时候名字前卫0.3-，750时候0.6-
        if($w == 180)$n = '0.3';
        if($w == 750)$n = '0.6';
        imagejpeg($image_p, $path . $n . "-" .$filename, 100);
//        return $percent. "-" .$filename;
    }


}