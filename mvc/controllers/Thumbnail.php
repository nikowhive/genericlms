<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Thumbnail extends Admin_Controller
{
    public function index(){
        $allpaths = ['uploads/images/','uploads/attach/','uploads/notice/','uploads/events/','uploads/holiday/','uploads/gallery/','uploads/holiday/'];
        $support = ['jpg','jpeg','png','gif'];
        $resolutions  = ['1500','1024','768','512','256','128','56'];
        foreach($allpaths as $path){
            if (file_exists($path)) {
                if ($handle = opendir($path)) {
                    $filesArray = [];
                    while (false !== ($entry = readdir($handle))) {
                        if ($entry != "." && $entry != "..") {
                            if(is_file($path.$entry)){
                                $explode = explode('.', $entry);
                                $ext = end($explode);
                                if(in_array($ext,$support)){
                                    $filesArray[] = $entry;
                                }	
                            }
                        }
                    }
                    if(customCompute($filesArray)){
                        foreach($filesArray as $fileArray){
                            $sourcePath = $path.$fileArray;
                            list($width, $height, $type, $attr) = getimagesize($sourcePath);
                            foreach($resolutions as $resolution){
                                if($width > $resolution || $height > $resolution){
                                    $filepath = $path.$resolution.'/'.$fileArray;
                                    $targetPath = $path.$resolution;
                                    if (!file_exists($targetPath)) {
                                        mkdir($targetPath, 0777, true);
                                    }
                                        // different resolution
                                    if(!file_exists($filepath)){
                                        resizeImageFromFolder($sourcePath,$targetPath,$resolution);
                                    }
                                }
                            }
                        }
                    }
                    closedir($handle);
                }
            }
        }
        
    }
}   