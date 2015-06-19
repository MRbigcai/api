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
        return $name;
    }
    
    public static function savePhotoWithClientData($path, $data)
    {
        $binary = PhotoUtility::getPhotoBinaryData($data);
        return PhotoUtility::savePhotoWithBinary($path, $binary);
    }
}